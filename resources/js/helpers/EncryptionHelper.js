import * as CryptoJS from 'crypto-js';

const _key = import.meta.env.VITE_APP_NAME + "to-do"
function encrypt(txt) {
    return CryptoJS.AES.encrypt(txt, _key).toString();
}
function decrypt(txtToDecrypt) {
    const text = txtToDecrypt == null ? "" : txtToDecrypt
    const decryptedText = CryptoJS.AES.decrypt(text, _key).toString(CryptoJS.enc.Utf8);
    return decryptedText == "" ? "[]" : decryptedText
}
export { encrypt, decrypt }
