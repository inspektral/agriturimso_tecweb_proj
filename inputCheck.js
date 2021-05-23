var dettagliForm = {
  "da": ["Da",/^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$/, "inserire la data nel formato gg/mm/aaaa"],
  "a": ["A",/^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$/, "inserire la data nel formato gg/mm/aaaa"],
  "nome": ["Nome",/([a-zA-Z])(\ )([a-zA-Z]){2,20}$/,"Il nome non può essere vuoto"],
  "cognome":["Cognome",/([a-zA-Z])(\ )([a-zA-Z]){2,20}$/,"Il cognome non può essere vuoto"],
  "email":["Email",,"Indirizzo email non valido"],
  "password":["Password",,"La password deve avere almeno 8 caratteri"],
  "commento":["Commento",/\.{10,}/,"Inserisci il tuo commento"],
}

function validatePrenota() {
  console.log("validatePrenota()")
  const da = document.getElementById("da")
  console.log(da.value)
  const a = document.getElementById("a")
  console.log(a.value)
  if (validateField(da) && validateField(a)) {
    if(checkDateDaA(da, a)) {
      return true
    }
    return false
  }
  return false
}

function validateComment() {
  console.log("validateComment()")
  const comm = document.getElementById("commento")
  return validateField(comm)
  return false
}

 function validateField(input) {
  let parent = input.parentNode;
	if(parent.childern.length == 2){
		parent.removeChild(parent.childern[1]);
	}

	let regex = dettagli_form[input.id][1];
	let text = input.value;

  console.log("validating field"+ input.value)

	if(text.search(regex) != 0){
		showError(input);
		return false;
	}
	return true;
 }

 function showError(input, errorMsg) {
  var element = document.createElement("strong");
	element.className = "error";
	element.appendChild(document.createTextNode(errorMsg));

	var parent = input.parentNode;
	parent.appendChild(element);
 }

function dateParse(dateString) {
  return new Date(dateString(6,10), dateString(3,5), dateString(0,2))
}

function checkDateDaA(da,a) {
  const dateDa = dateParse(da.value)
  const dateA = dateParse(a.value)
  let validDates = true
  const errPast = "la data immessa è già passata"
  const errIncompatible = "La data di inizio deve precedere quella di fine"
  if (dateDa < Date.now()) {
    showError(da, errPast)
    validDates = false
  }
  if (dateA < Date.now()) {
  showError(A, errPast)
  validDates = false
}
if (validDates) {
  if(dateA<dateDa) {
    showError(da, errIncompatible)
    showError(a,errIncompatible)
    return false
  }
  return true
}
return false
}

