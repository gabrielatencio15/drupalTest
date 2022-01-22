'use strict'

function consultarApi(u, cit, codcoun, k)
{
    let url = u;

    if(url == '' || url == '0')
    {
        //Alertar error de una vez;
        return false;
    }

    if(cit != '' || cit != '0' || k != '' || k != '0')
    {
        url = u+'?q='+cit+','+codcoun+'&appid='+k;
    }

    $.ajax({
        url: url,
        type: 'GET',
        data: true,
        success: function(res) {
            console.log(res['main']);
        },
        error: function(res)
        {
            //Alertar error de una vez;
            console.log(res);
        }
    });
}

$("#ciudad_value").change(function(){
    
    let cou = $('select option:selected').text();
    let codcoun = $('select option:selected').val();

    consultarApi(atob($("#weather_u").val()), cou, codcoun, atob($("#weather_k").val()));
 
});