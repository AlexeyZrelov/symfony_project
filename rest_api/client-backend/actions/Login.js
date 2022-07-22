import {baseURL} from '../src/config'
import {email} from '../src/config'
import {password} from '../src/config'

export class Login {

    constructor(action = null)
    {
        if(action !== null)
        {
            this.sendTokenHeader().then( () => {
                eval("this."+action+"()")
            } )
        }
    }

    sendTokenHeader()
    {
        return new Promise ( (resolve) => {
            axios.defaults.headers.common = {
                'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
            }
            resolve()
        } )
    }

    getJWTToken() // login
    {
        let params = {
            "email": email,
            "password": password
        }

        let config = {
            headers: {
                'accept': 'application/json',
            }
        }

        axios.post(baseURL+'/authentication_token', params, config)
            .then((response) => {
                localStorage.setItem("jwt_token", response.data.token)
                localStorage.setItem("user_id", response.data.id)
                // console.log(response.data.token);
            }).catch((error) => {
            // console.log(error)
        })


    }

    logout()
    {
        localStorage.removeItem('jwt_token')
        localStorage.removeItem('user_id')
    }

    handle401Error()
    {
        let p = document.getElementById("needs-login");
        p.style.display = 'block';
        let a = document.createElement('a');
        a.style.color = "#FF0000"
        let linkText = document.createTextNode("Invalid authorization, click to login");
        a.appendChild(linkText);
        a.href = "#"
        p.appendChild(a);
        document.body.appendChild(p);
    }

}