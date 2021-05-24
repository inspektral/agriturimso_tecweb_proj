var dettagliForm = {
  "da": ["Da","(0[1-9]|[12][0-9]|3[0-1])\/(0[0-9]|1[012])\/((20|19)[0-9][0-9])", "inserire la data nel formato gg/mm/aaaa"],
  "a": ["A","(0[1-9]|[12][0-9]|3[0-1])\/(0[0-9]|1[012])\/((20|19)[0-9][0-9])", "inserire la data nel formato gg/mm/aaaa"],
  "nome": ["Nome","([a-zA-Z])(\ )([a-zA-Z]){2,20}","Il nome non può essere vuoto"],
  "cognome":["Cognome","([a-zA-Z])(\ )([a-zA-Z]){2,20}","Il cognome non può essere vuoto"],
  "email":["Email",,"Indirizzo email non valido"],
  "password":["Password",,"La password deve avere almeno 8 caratteri"],
  "testo":["Testo","\.{10,}","Inserisci il tuo commento"],
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
  }
  catch {}
  return false
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

 function validateField(input) {
  let parent = input.parentNode;

	if(parent.children.length == 2){
		parent.removeChild(parent.children[1]);
	}
	let regex = dettagliForm[input.id][1];
	let text = input.value;

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

