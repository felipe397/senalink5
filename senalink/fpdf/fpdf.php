<?php
/******************************************************************************* 
* FPDF                                                                         * 
*                                                                              * 
* Version: 1.86                                                                * 
* Date:    2023-06-25                                                          * 
* Author:  Olivier PLATHEY                                                     * 
*******************************************************************************/ 

define('FPDF_FONTPATH', dirname(__FILE__).'/font/'); // Definición de la constante

class FPDF 
{ 
    const VERSION = '1.86'; 
    protected $page;               // current page number 
    protected $n;                  // current object number 
    protected $offsets;            // array of object offsets 
    protected $buffer;             // buffer holding in-memory PDF 
    protected $pages;              // array containing pages 
    protected $state;              // current document state 
    protected $compress;           // compression flag 
    protected $iconv;              // whether iconv is available 
    protected $k;                  // scale factor (number of points in user unit) 
    protected $DefOrientation;     // default orientation 
    protected $CurOrientation;     // current orientation 
    protected $StdPageSizes;       // standard page sizes 
    protected $DefPageSize;        // default page size 
    protected $CurPageSize;        // current page size 
    protected $CurRotation;        // current page rotation 
    protected $PageInfo;           // page-related data 
    protected $wPt, $hPt;          // dimensions of current page in points 
    protected $w, $h;              // dimensions of current page in user unit 
    protected $lMargin;            // left margin 
    protected $tMargin;            // top margin 
    protected $rMargin;            // right margin 
    protected $bMargin;            // page break margin 
    protected $cMargin;            // cell margin 
    protected $x, $y;              // current position in user unit 
    protected $lasth;              // height of last printed cell 
    protected $LineWidth;          // line width in user unit 
    protected $fontpath;           // directory containing fonts 
    protected $CoreFonts;          // array of core font names 
    protected $fonts;              // array of used fonts 
    protected $FontFiles;          // array of font files 
    protected $encodings;          // array of encodings 
    protected $cmaps;              // array of ToUnicode CMaps 
    protected $FontFamily;         // current font family 
    protected $FontStyle;          // current font style 
    protected $underline;          // underlining flag 
    protected $CurrentFont;        // current font info 
    protected $FontSizePt;         // current font size in points 
    protected $FontSize;           // current font size in user unit 
    protected $DrawColor;          // commands for drawing color 
    protected $FillColor;          // commands for filling color 
    protected $TextColor;          // commands for text color 
    protected $ColorFlag;          // indicates whether fill and text colors are different 
    protected $WithAlpha;          // indicates whether alpha channel is used 
    protected $ws;                 // word spacing 
    protected $images;             // array of used images 
    protected $PageLinks;          // array of links in pages 
    protected $links;              // array of internal links 
    protected $AutoPageBreak;      // automatic page breaking 
    protected $PageBreakTrigger;   // threshold used to trigger page breaks 
    protected $InHeader;           // flag set when processing header 
    protected $InFooter;           // flag set when processing footer 
    protected $AliasNbPages;       // alias for total number of pages 
    protected $ZoomMode;           // zoom display mode 
    protected $LayoutMode;         // layout display mode 
    protected $metadata;           // document properties 
    protected $CreationDate;       // document creation date 
    protected $PDFVersion;         // PDF version number 

    /******************************************************************************* 
    *                               Public methods                                 * 
    *******************************************************************************/ 

    function __construct($orientation='P', $unit='mm', $size='A4') 
    { 
        // Initialization of properties 
        $this->state = 0; 
        $this->page = 0; 
        $this->n = 2; 
        $this->buffer = ''; 
        $this->pages = array(); 
        $this->PageInfo = array(); 
        $this->fonts = array(); 
        $this->FontFiles = array(); 
        $this->encodings = array(); 
        $this->cmaps = array(); 
        $this->images = array(); 
        $this->links = array(); 
        $this->InHeader = false; 
        $this->InFooter = false; 
        $this->lasth = 0; 
        $this->FontFamily = ''; 
        $this->FontStyle = ''; 
        $this->FontSizePt = 12; 
        $this->underline = false; 
        $this->DrawColor = '0 G'; 
        $this->FillColor = '0 g'; 
        $this->TextColor = '0 g'; 
        $this->ColorFlag = false; 
        $this->WithAlpha = false; 
        $this->ws = 0; 
        $this->iconv = function_exists('iconv'); 
        // Font path 
        $this->fontpath = FPDF_FONTPATH; 
        // Core fonts 
        $this->CoreFonts = array('courier', 'helvetica', 'times', 'symbol', 'zapfdingbats'); 
        // Scale factor 
        if($unit=='pt') 
            $this->k = 1; 
        elseif($unit=='mm') 
            $this->k = 72/25.4; 
        elseif($unit=='cm') 
            $this->k = 72/2.54; 
        elseif($unit=='in') 
            $this->k = 72; 
        else 
            $this->Error('Incorrect unit: '.$unit); 
        // Page sizes 
        $this->StdPageSizes = array('a3'=>array(841.89,1190.55), 'a4'=>array(595.28,841.89), 'a5'=>array(420.94,595.28), 
            'letter'=>array(612,792), 'legal'=>array(612,1008)); 
        $size = $this->_getpagesize($size); 
        $this->DefPageSize = $size; 
        $this->CurPageSize = $size; 
        // Page orientation 
        $orientation = strtolower($orientation); 
        if($orientation=='p' || $orientation=='portrait') 
        { 
            $this->DefOrientation = 'P'; 
            $this->w = $size[0]; 
            $this->h = $size[1]; 
        } 
        elseif($orientation=='l' || $orientation=='landscape') 
        { 
            $this->DefOrientation = 'L'; 
            $this->w = $size[1]; 
            $this->h = $size[0]; 
        } 
        else 
            $this->Error('Incorrect orientation: '.$orientation); 
        $this->CurOrientation = $this->DefOrientation; 
        $this->wPt = $this->w*$this->k; 
        $this->hPt = $this->h*$this->k; 
        // Page rotation 
        $this->CurRotation = 0; 
        // Page margins (1 cm) 
        $margin = 28.35/$this->k; 
        $this->SetMargins($margin,$margin); 
        // Interior cell margin (1 mm) 
        $this->cMargin = $margin/10; 
        // Line width (0.2 mm) 
        $this->LineWidth = .567/$this->k; 
        // Automatic page break 
        $this->SetAutoPageBreak(true,2*$margin); 
        // Default display mode 
        $this->SetDisplayMode('default'); 
        // Enable compression 
        $this->SetCompression(true); 
        // Metadata 
        $this->metadata = array('Producer'=>'FPDF '.self::VERSION); 
        // Set default PDF version number 
        $this->PDFVersion = '1.3'; 
    }

    // ... (resto del código)
}
?>
