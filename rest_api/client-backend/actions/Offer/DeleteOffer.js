import {baseURL} from '../../src/config.js';


export class DeleteOffer {


    DeleteOffer(delete_url)
    {
        axios.delete(baseURL + delete_url)
            .then((response) => {
                console.log(response);
            })
            .catch( (error) => {
                console.log(error);
            });
    }
}