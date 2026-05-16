function setTaal(taal) {
  document.querySelectorAll('.vertaal').forEach(el => {
    el.textContent = el.dataset[taal];
  });
  document.getElementById('knop-nl').style.fontWeight = taal === 'nl' ? 'bold' : 'normal';
  document.getElementById('knop-li').style.fontWeight = taal === 'li' ? 'bold' : 'normal';
  localStorage.setItem('taal', taal);
}

// Bij het laden: vorige keuze terughalen, anders Nederlands
const opgeslagenTaal = localStorage.getItem('taal') || 'nl';
setTaal(opgeslagenTaal);