import {baseURL} from '../src/config.js';

export class Products {

    constructor()
    {
        axios.get(baseURL + '/api/products?pagination=false')
            .then((response) => {
                // console.log(response.data['hydra:member']);
                response.data['hydra:member'].forEach(product => {
                    this.addOptionElement(product.name, product.id)
                });
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });

    }

    addOptionElement(text, value)
    {
        let option = document.createElement("option");
        option.text = text;
        option.value = value;
        let select = document.getElementById("product");
        select.appendChild(option);
    }
}
