import {baseURL} from './config.js';
import { show } from './view.js';
import {pagination} from "./pagination.js";
import {router} from "./router.js";

export const getData = (event) => {

    axios.get(baseURL + router(event))
        .then(function (response) {

            const searchButton = document.getElementById('search-button');
            const orderByName = document.getElementById("order-by-name");
            const filetrWithImages = document.getElementById("filter-with-images-only");

            searchButton.addEventListener("click", getData);
            orderByName.addEventListener("click", getData);
            filetrWithImages.addEventListener("click", getData);

            orderByName.style.display = 'block';
            filetrWithImages.style.display = 'block';

            if ( typeof orderByName.order === 'undefined' )
                orderByName.order = 'asc';
            else if ( orderByName.order == 'asc' ) orderByName.order = 'desc'
            else orderByName.order = 'asc';

            pagination(response.data);
            show(response.data['hydra:member']);
            // console.log(response.data);
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        });

}


// export const getData = (event) => {
//
//     axios.get(baseURL + router(event))
//         .then(function (response) {
//
//             const orderByName = document.getElementById("order-by-name");
//             const filetrWithImages = document.getElementById("filter-with-images-only");
//
//             orderByName.addEventListener("click", getData);
//             filetrWithImages.addEventListener("click", getData);
//
//             orderByName.style.display = 'block';
//             filetrWithImages.style.display = 'block';
//
//             if ( typeof orderByName.order === 'undefined' )
//                 orderByName.order = 'asc';
//             else if ( orderByName.order == 'asc' ) orderByName.order = 'desc'
//             else orderByName.order = 'asc';
//
//             pagination(response.data);
//             show(response.data['hydra:member']);
//             // console.log(response.data);
//         })
//         .catch(function (error) {
//             // handle error
//             console.log(error);
//         });
//
// }


// export const getData = (event) => {
//
//     axios.get(baseURL + router(event))
//         .then(function (response) {
//
//             const orderByName = document.getElementById("order-by-name");
//
//             orderByName.addEventListener("click", getData);
//
//             orderByName.style.display = 'block';
//
//             if ( typeof orderByName.order === 'undefined' )
//                 orderByName.order = 'asc';
//             else if ( orderByName.order == 'asc' ) orderByName.order = 'desc'
//             else orderByName.order = 'asc';
//
//             pagination(response.data);
//             show(response.data['hydra:member']);
//             // console.log(response.data);
//         })
//         .catch(function (error) {
//             // handle error
//             console.log(error);
//         });
//
// }


// export const getData = (event) => {
//
//     axios.get(baseURL + router(event))
//         .then(function (response) {
//
//             const orderByName = document.getElementById("order-by-name");
//
//             orderByName.addEventListener("click", getData);
//
//             orderByName.style.display = 'block';
//
//             if ( typeof orderByName.order === 'undefined' )
//                 orderByName.order = 'asc';
//             else if ( orderByName.order == 'asc' ) orderByName.order = 'desc'
//             else orderByName.order = 'asc';
//
//             pagination(response.data);
//             show(response.data['hydra:member']);
//             // console.log(response.data);
//         })
//         .catch(function (error) {
//             // handle error
//             console.log(error);
//         });
//
// }


// export const getData = (event) => {
//
//     axios.get(baseURL + router(event))
//         .then(function (response) {
//             pagination(response.data);
//             show(response.data['hydra:member']);
//             // console.log(response.data);
//         })
//         .catch(function (error) {
//             // handle error
//             console.log(error);
//         });
//
// }


// export const getData = (event) => {
//
//     if(typeof event.target.nextLink !== 'undefined')
//         var url = event.target.nextLink
//     else
//         var url = '/api/products'
//     axios.get(baseURL + url)
//         .then(function (response) {
//             pagination(response.data);
//             show(response.data['hydra:member']);
//             // console.log(response.data);
//         })
//         .catch(function (error) {
//             // handle error
//             console.log(error);
//         });
//
// }


// export const getData = () => {
//
//     // axios.get('http://localhost:8000/api/products').then(function (response) {
//     axios.get(baseURL + '/api/products').then(function (response) {
//         // console.log(response)
//         show(response.data['hydra:member']);
//
//     }).catch(function (error) {
//         console.log(error);
//     })
//
// }

