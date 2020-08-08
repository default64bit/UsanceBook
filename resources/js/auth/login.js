import crypto from 'crypto-js';

const state =  createRandomString(40);
const verifier =  createRandomString(128);

var code_challenge = '';
var client_id = '1';
var base_url = document.querySelector('meta[name="base_url"]').getAttribute('content');
var redirect_uri = `${base_url}/login/callback`;
var response_type = 'code';
var code_challenge_method = 'S256';

function createRandomString(num){
    return [...Array(num)].map(() => Math.random().toString(36)[2]).join('');
}
function base64Url(string){
    return string.toString(crypto.enc.Base64)
        .replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

function preLogin(){
    code_challenge = base64Url(crypto.SHA256(verifier));
    localStorage.setItem('state',state);
    localStorage.setItem('verifier',verifier);
}

function goToLoginPage(action = '1'){
    preLogin();
    let url = `${base_url}/oauth/authorize?client_id=${client_id}&redirect_uri=${redirect_uri}&response_type=${response_type}&state=${state}&code_challenge=${code_challenge}&code_challenge_method=${code_challenge_method}&a=${action}`;
    window.location.href = url;
}

export default {
    goToLoginPage
}