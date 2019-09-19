document.getElementById('formElem').addEventListener('submit', async (e) => {
  e.preventDefault();
let response = await fetch('wordstat.php', {
  method: 'POST',
  body: new FormData(formElem)
});
document.querySelector('.report').insertAdjacentHTML('beforeend', `<a class="file" href ="./get-file.php">Скачать отчет</a>`);
});

//После скачивания отчета, страница перезагрузится
document.querySelector('.report').addEventListener('click', (e) => {
  setTimeout( () => {
    location.reload()
  }, 3000);

});
