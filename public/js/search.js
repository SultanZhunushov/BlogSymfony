"use strict";

function search() {
    let searchBar = document.getElementById("search_bar"),
    filter = searchBar.value.toUpperCase(),
    film = document.getElementById("div"),
    a = film.getElementsByTagName("a");

    for (let i = 0; i < a.length; i++) {    
        let title = a[i].getElementsByTagName("h3")[0],
            category = a[i].getElementsByTagName("small")[0],
            txtTitle = title.textContent || titly.innerHTML,
            txtCategory = category.textContent || category.innerHTML;
        if (txtTitle.toUpperCase().indexOf(filter) > -1 || txtCategory.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}