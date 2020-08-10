function setCookie(name, value, time){
    var d = new Date();
    d.setTime(d.getTime() + (time*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function getCookie(name){
    var name = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(let i=0; i<ca.length; i++){
        var c = ca[i];
        while(c.charAt(0) == ' '){
            c = c.substring(1);
        }
        if(c.indexOf(name) == 0){
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

async function updateToken(){
    let is_token_updated = false;

    await axios({
        url: '/refresh_token',
        method: 'post',
        data: {},
    }).then(response=>{
        is_token_updated = true;
        setCookie('access_token',response.data.access_token,20*60);
        console.log('Token has been refreshed');
    }).catch(error=>{
        is_token_updated = false;
        console.log(error);
    });

    return new Promise((resolve,reject)=>{
        if(is_token_updated){
            resolve();
        }else{ reject('Failed To Update Toekn'); }
    });
}

async function isTokenValid(){
    let access_token = getCookie('access_token');
    let is_token_valid = false;

    await axios({
        url: '/api/v1/user',
        method: 'get',
        headers: {
            'Authorization': `Bearer ${access_token}`,
            'Content-Type': 'application/json'
        },
    }).then(response=>{
        is_token_valid = true;
    }).catch(error=>{ is_token_valid = false; });

    if(!is_token_valid){
        await updateToken().then(()=>{
            is_token_valid = true;
        }).catch(()=>{
            setCookie('access_token','',0);
            is_token_valid = false;
        });
    }

    return new Promise((resolve,reject)=>{
        if(is_token_valid){
            resolve();
        }else{ reject('Token Is Not Valid'); }
    });
}

async function getToken(){
    let access_token = getCookie('access_token');
    if(access_token != '' && access_token != undefined && access_token != null){
        return access_token;
    }else{
        await updateToken().then(()=>{
            access_token = getCookie('access_token');
        }).catch(()=>{
            access_token = null;
        });
        return access_token;
    }
}

export default {
    isTokenValid,
    getToken
}