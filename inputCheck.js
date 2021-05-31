var dettagliForm = {
  "da": ["Da","(0[1-9]|[12][0-9]|3[0-1])\/(0[0-9]|1[012])\/((20|19)[0-9][0-9])", "inserire la data nel formato gg/mm/aaaa"],
  "a": ["A","(0[1-9]|[12][0-9]|3[0-1])\/(0[0-9]|1[012])\/((20|19)[0-9][0-9])", "inserire la data nel formato gg/mm/aaaa"],
  "nome": ["Nome","([a-zA-Z]){2,20}","Il nome non può essere vuoto"],
  "cognome":["Cognome","([a-zA-Z]){2,20}","Il cognome non può essere vuoto"],
  "email":["Email",/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,"Indirizzo email non valido"],
  "password":["Password",/^.{8,}/,"La password deve avere almeno 8 caratteri"],
  "testo":["Testo","\.{10,}","Inserisci il tuo commento"],
  "name": ["Name","([a-zA-Z]){2,20}","inserire il nome della camera"],
  "people": ["People", /^\d{1,2}/, "inserire il numero di posti letto"],
  "price": ["Price", /^\d{1,4}/, "inserire il prezzo della camera"],
  "description" : ["NewsDescription", /^.{10,}/, "inserire la descrizione della notizia"]
}

function validateNews() {
  return validateField(document.getElementById("description"))
}

function validateRoom(){
  try {
    const nome = document.getElementById("name")
    const posti = document.getElementById("people")
    const price = document.getElementById("price")
    const descrizione = document.getElementById("imgLongDesc")
    const validNome = validateField(nome)
    const validPosti = validateField(posti)
    const validPrice = validateField(price)
    const validDescrizione = validateField(descrizione)
    return validNome && validPosti && validPrice
  }
  catch {
    return false
  }
}

function validatePrenota() {
  try {
    const da = document.getElementById("da")
    const a = document.getElementById("a")
    let validDa = validateField(da)
    let validA = validateField(a)
    if (validDa && validA) {
      if(checkDateDaA(da, a)) {
        return true
      }
      return false
    }
    return false
  }
  catch {
    return false
  }
}

function validateComment() {
  try {
    const comm = document.getElementById("testo")
    return validateField(comm)
  }
  catch {
    return false
  }
}

function validateRegistrati() {
  try {
    console.log("validateRegistrati called");
    const nome = document.getElementById("nome")
    const cognome = document.getElementById("cognome")
    const validNome = validateField(nome)
    const validCognome = validateField(cognome)
    const validCredents = validateAccedi()
    return validNome && validCognome && validCredents
  }
  catch {
    return false
  }
}

function validateAccedi() {
  try {
    const email = document.getElementById("email")
    const password = document.getElementById("password")
    const validEmail = validateField(email)
    const validPassword = validateField(password)
    return validEmail && validPassword
  }
  catch {
    return false
  }
}

 function validateField(input) {
  const parent = input.parentNode;

	if(parent.children.length == 2){
		parent.removeChild(parent.children[1]);
	}
	const regex = dettagliForm[input.id][1];
	const text = input.value;

	if(text.search(regex) != 0){
		showError(input, dettagliForm[input.id][2]);
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
  return new Date(dateString.substring(6,10)+"-"+dateString.substring(3,5)+"-"+dateString.substring(0,2))
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
