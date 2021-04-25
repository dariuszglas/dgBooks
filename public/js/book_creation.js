
$('document').ready(initCreateBook);

function initCreateBook()
{
    $("#book_isbnField").on("change", isbnFieldChanged);
}

function isbnFieldChanged()
{
    let isbnFieldVal = $("#book_isbnField").val();
    $("#book_isbn").val(isbnFieldVal.replaceAll('-','')).trigger("change");
}