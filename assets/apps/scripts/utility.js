/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(window).on("popstate", function(e) {
    if (e.originalEvent.state !== null) {
        location.reload();
    }
});

$(document).ready(function() {
    
    //FUNZIONE CHE RITORNA I PARAMETRI DELL'URL. Es. $.urlParam('res')
    $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
           return null;
        }
        else{
           return decodeURI(results[1]) || 0;
        }
    }
});

function pulisciRefereStorico(){
    var urlReferer = get_referer();
    
    if(urlReferer.length) {
    // are the new history methods available ?
        if(window.history != undefined && window.history.pushState != undefined) {
            // if pushstate exists, add a new state the the history, this changes the url without reloading the page
            window.history.pushState({}, document.title, urlReferer);
        }
    }
}

//FUNZIONE CHE RIMUOVE I PARAMETRI DA UN URL
function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function get_referer(){
    //Recupero url
    var urlReferer = location.href;
    //Rimuovo dalla stringa eventuali ancora nell'url
    var locationTmp = urlReferer.split("#")[0];
    //ripulisco la variabile &tab=
    locationTmp = removeParam("tab", locationTmp);
    //ripulisco la variabile &res=
    urlReferer = removeParam("res", locationTmp);

    return urlReferer;
}

//FUNZIONE DI VERIFICA FORMATO SUL CODICE FISCALE
function controllaCF(CodiceFiscale) {
  // Definisco un pattern per il confronto
  var pattern = /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/;

  // utilizzo il metodo search per verificare che il valore inserito nel campo
  // di input rispetti la stringa di verifica (pattern)
  if (CodiceFiscale.search(pattern) == -1)
  {
    return false;
  }else{
     return true;
  }
}

//FUNZIONE PER PRINTARE UN OGGETTO JAVASCRIPT
//function print_r(myObject, log=false){
//    var ret = '';
//    $.each(myObject, function(key, element) {
//        ret = ret + 'key: ' + key + ' => ' + 'value: ' + element+'\n';
//    });
//    if(!log){
//        alert(ret);
//    }else{
//        console.log(ret);
//    }
//}