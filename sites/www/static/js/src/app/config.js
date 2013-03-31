
seajs.config({
    'map': [
        [ /^(.*\.(?:css|js))(.*)$/i, '$1?'+(new Date()).getTime() ]
    ]
});

