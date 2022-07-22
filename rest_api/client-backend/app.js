import {Products} from './actions/Products.js';
import {AddOffer} from './actions/Offer/AddOffer.js';
import {GetOffers} from './actions/Offer/GetOffers.js';
import {DeleteOffer} from './actions/Offer/DeleteOffer.js';
import {Upload} from './actions/Upload.js';

// offer
new Products
let addOffer = document.getElementById("add-offer")
addOffer.addEventListener("click", () => {
    let url = document.getElementById("url")
    let price = document.getElementById("price")
    let currency = document.getElementById("currency")
    let product = document.getElementById("product")
    let product_id = product.options[product.selectedIndex].value;
    let Offer = new AddOffer();
    Offer.AddOffer(url.value,parseFloat(price.value),currency.value,product_id);
});

new GetOffers()
let deleteOffer = document.getElementById("delete-offer-button")
deleteOffer.addEventListener("click", () => {
    let offer = document.getElementById("delete-offer")
    let offer_id = offer.options[offer.selectedIndex].value;
    let deleteOffer = new DeleteOffer();
    deleteOffer.DeleteOffer(offer_id)
});

let upload = document.getElementById("upload-file")
upload.addEventListener("change",
    e => {

        let files = e.target.files || e.dataTransfer.files;
        if (!files.length) {
            console.log('no files');
        }
        let product = document.getElementById("product")
        let product_id = product.options[product.selectedIndex].value;

        if (product_id === "") return
        // console.log(files);
        // console.log(files[0].name)
        new Upload(files, product_id)

    });