function ajax(getVal){
    if (getVal == '') { 
        $('#demo').html(''); 
        return;
    } else {
        if (window.XMLHttpRequest) { // kiem tra browser co ho tro xmlhttprequest khong
            xmlHttp = new XMLHttpRequest();
        } 
        xmlHttp.onreadystatechange = function (){ // ajax
            if (this.readyState == 4 && this.status == 200) {
                $('#demo').html(this.responseText);
            }
        }
        xmlHttp.open('GET', 'handler.php?val='+getVal, true);
        xmlHttp.send()
    }
}