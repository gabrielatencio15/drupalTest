'use strict'

function mostrarWeatherDashboard(result, cit, unit)
{
    console.log(result);
    let temperature_indicator = 'F';

    if(unit == 'metric')
    {
        temperature_indicator = 'C'
    }

    let today = new Date();
    let time = today.getHours() + ":" + ((today.getMinutes() <= 9) ? '0'+today.getMinutes() : today.getMinutes());
    

    $("#city_name").text(cit);
    $("#temperature_indicator").text(Math.trunc(result.temp)+'° '+temperature_indicator);
    $("#time_query").text(time);
    $("#humidity_indicator").text(result.humidity + '%');
    $("#pressure_indicator").text(result.pressure + ' hPa');
    $("#mintemp_indicator").text(Math.trunc(result.temp_min)+'° '+temperature_indicator);
    $("#maxtemp_indicator").text(Math.trunc(result.temp_max)+'° '+temperature_indicator);
    
    
}

function consultarApi(ur, cit, codcoun, k, unit)
{
    let url = ur;

    if(url == '' || url == '0')
    {
        //Alertar error de una vez;
        return false;
    }

    if(cit != '' || cit != '0' || k != '' || k != '0')
    {
        url = ur+'?q='+cit+','+codcoun+'&appid='+k+'&units='+unit;
    }

    $.ajax({
        url: url,
        type: 'GET',
        data: true,
        success: function(res) {
            mostrarWeatherDashboard(res['main'], cit, unit);
            return;
        },
        error: function(res)
        {
            //Alertar error de una vez;
            console.log(res);
        }
    });
}

$("#ciudad_value").change(function(){
    
    let cou = $('#ciudad_value option:selected').text();
    let codcoun = $('#ciudad_value option:selected').val();
    let weatherunit = $('#weather_unit').val();

    if(codcoun == '')
    {
        return;
    }

    $("#ciudad_value option[value='']").remove();

    consultarApi(atob($("#weather_u").val()), cou, codcoun, atob($("#weather_k").val()), atob(weatherunit));
 
});



