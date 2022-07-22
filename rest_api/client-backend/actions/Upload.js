import {baseURL} from '../src/config.js';

// export class Upload extends Login  {
export class Upload {

    constructor(files,product_id)
    {
        // super('Upload')
        // this.Upload()
        this.files = files
        this.product_id = product_id
        this.Upload()
    }

    Upload()
    {
        let data = new FormData();

        // data.append('imageFile', this.files[0].name)
        data.append('imageFile', this.files[0].name)
        // data.append('imageFile', this.files[0], this.files[0].name)
        data.append('product_id', this.product_id)

        let config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }

        axios.post(baseURL+'/api/upload', data, config)
            .then((response) => {
                console.log(response);
            }).catch((error) => {
            console.log(error);
            // if( error.response.data.code === "401" )
            // {
            //     this.handle401Error()
            // }
        })
    }

}