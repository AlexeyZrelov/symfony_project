import {baseURL} from '../../src/config.js';

export class GetOffers {

    constructor()
    {

        axios.get(baseURL + '/api/offers')
            .then((response) => {
                // console.log(response.data['hydra:member']);
                response.data['hydra:member'].forEach(offer => {
                    this.addOptionElement(offer.url, offer['@id'])
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
        let select = document.getElementById("delete-offer");
        select.appendChild(option);
    }
}