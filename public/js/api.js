var cyclum = {
    ini: function(group_id_param, default_status_param){
        var startTime = new Date().getTime(),
            endTime = 0,
            seconds = 0,
            URL = window.location.href.replace('https://', '').replace('http://', ''),
            userId = getCookie('userId'),
            group = group_id_param,
            default_status = default_status_param;
            
        if(URL.substr(0, 4).toLowerCase() == "www.") URL = URL.substr(4);
        
        function getSeconds(miliseconds){
            return parseInt(miliseconds/1000);
        }
        function getCookie(name){
            var value = '; ' + document.cookie;
            var parts = value.split('; ' + name + '=');
            if (parts.length == 2) return parts.pop().split(';').shift();
            else return '';
        }
        
        function setCookie(){
            $.ajax({
                type: 'GET',
                url: '/setcookie',
                data: 'user_id=' + getCookie('userId') + '&group_id=' + group + '&default_status=' + default_status,
                cache: false,
                success: function(response){
                    if (response != ''){
                        document.cookie = 'userId=' + response;
                        userId = response;
                    } else {
                        console.log('ERROR: Setting cookie.');
                    }
                }
            });  
        }
        function setVisitTime(){
            endTime = new Date().getTime();
            seconds = getSeconds(endTime - startTime);
    
            $.ajax({
                type: 'GET',
                url: '/setvisittime',
                data: 'group_id=' + group + '&url=' + URL + '&seconds=' + seconds,
                cache: false,
                async: false,
                success: function(response) {
                    if (response == '200') {
                        console.log('SUCCESS: Setting visited time.');
                    } else {
                        console.log('ERROR: Setting visited time.');
                    }
                }
            });
        }
        function setStats(data){
            $.ajax({
                type: 'GET',
                url: '/savestats',
                data: data,
                cache: false,
                success: function(response){
                    if (response == '200'){
                        console.log('SUCCESS: Saving user stats.');
                    } else if(response == '403') {
                        console.log('ERROR: Forbidden access saving user stats.');
                    }
                }
            });
        }
    
        $('[data-cyclum]').each(function(){
            var type  = $(this).data('cyclum'),
                name  = '',
                value = '';
            
            if(typeof($(this).data('cyclum-value')) != 'undefined') name = $(this).data('cyclum-value');
            if(typeof($(this).data('cyclum-amount')) != 'undefined' && type == 'variable') value = $(this).data('cyclum-amount');
            
            $(this).click(function(){
                var data = 'type=' + type + '&name=' + name + '&value=' + value + '&user_id=' + userId + '&group_id=' + group;
                setStats(data);
            });
        });
    
        // Ask server for Hashed userId Cookie
        $(window).load(function(){
            setCookie();
        });
        $(window).unload(function(){
            setVisitTime();
        });
        
        cyclum.variable = {
            modify: function(name, amount){
                var data = 'type=variable&name=' + name + '&value=' + amount + '&user_id=' + userId + '&group_id=' + group;
                setStats(data);
            }
        };
        cyclum.state = {
            modify: function(name){
                var data = 'type=state&name=' + name + '&user_id=' + userId + '&group_id=' + group;
                setStats(data);
            }
        };
    }
}
/* 
 * Another way is just checking buttons which have the 
 * data attribute, such as 'data-cyclum' or
 * the others
 *
 * @ $('button[data-cyclum]').each(function(){
 *
 * The problem with this is that we'll be depending on the
 * user, he should use only buttons or inputs, but maybe
 * we could check for buttons or inputs with the data
 * attribute and which are type='submit'
 *
 * Another way is just checking all the elements which have
 * 'data-cyclum' on their attributes
 *
 * @ $('[data-cyclum]').each(function(){
 */