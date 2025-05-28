function goBack() {
  if (document.referrer !== "") {
    history.back();
  } else {
    window.location.href = "/"; // o la ruta que tú quieras
  }
}