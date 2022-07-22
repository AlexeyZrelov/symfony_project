var array = ['?myParam=1','','',''];

function cssFilterApplied(target)
{
    target.style.cursor = 'default';
    target.style["pointer-events"] = 'none';
    target.style["text-decoration"] = 'line-through';
}
function cssFilterReset(target)
{
    target.style.cursor = '';
    target.style["pointer-events"] = '';
    target.style["text-decoration"] = '';
}
export function router(event) {
    if (typeof event.target.order !== 'undefined') {
        array[1] = '&order[name]=' + event.target.order;
        return '/api/products' + array[0] + array[1] + array[2] + array[3];
    } else if (event.target.id == 'filter-with-images-only') {
        cssFilterApplied(event.target);
        array[2] = '&exists[image]=true';
        return '/api/products' + array[0] + array[1] + array[2] + array[3];
    } else if (event.target.value == 'search') {
        var imagesFilter = document.getElementById("filter-with-images-only");
        cssFilterReset(imagesFilter);

        array[1] = '';
        array[2] = '';

        array[3] = '&name=' + document.getElementById("searched-text").value;
        return '/api/products' + array[0] + array[3];
    } else if (typeof event.target.nextLink !== 'undefined') {
        return event.target.nextLink;
    } else {
        array[1] = '';
        array[2] = '';
        array[3] = '';
        var imagesFilter = document.getElementById("filter-with-images-only");
        cssFilterReset(imagesFilter);
        return '/api/products?myParam=true';
    }
}


// function cssFilterApplied(target)
// {
//     target.style.cursor = 'default';
//     target.style["pointer-events"] = 'none';
//     target.style["text-decoration"] = 'line-through';
// }
// function cssFilterReset(target)
// {
//     target.style.cursor = '';
//     target.style["pointer-events"] = '';
//     target.style["text-decoration"] = '';
// }
// export function router(event)
// {
//     if( typeof event.target.order !== 'undefined'  )
//     {
//         array[1] =  '&order[name]='+event.target.order;
//         return '/api/products'+array[0]+array[1]+array[2]+array[3];
//     }
//     else if( event.target.id == 'filter-with-images-only'  )
//     {
//         cssFilterApplied(event.target);
//         array[2] = '&exists[image]=true';
//         return '/api/products'+array[0]+array[1]+array[2]+array[3];
//     }
//     else if (typeof event.target.nextLink !== 'undefined')
//     {
//         return event.target.nextLink;
//     }
//     else
//     {
//         array[1] = '';
//         array[2] = '';
//         array[3] = '';
//         var imagesFilter = document.getElementById("filter-with-images-only");
//         cssFilterReset(imagesFilter);
//         return '/api/products?myParam=true';
//     }
// }


// export function router(event)
// {
//     if( typeof event.target.order !== 'undefined'  )
//     {
//         array[1] =  '&order[name]='+event.target.order;
//         return '/api/products'+array[0]+array[1]+array[2]+array[3];
//     }
//     else if (typeof event.target.nextLink !== 'undefined')
//     {
//         return event.target.nextLink;
//     }
//     else
//     {
//         array[1] = '';
//         array[2] = '';
//         array[3] = '';
//         return '/api/products?myParam=true';
//     }
// }


// export function router(event)
// {
//     if (typeof event.target.nextLink !== 'undefined')
//     {
//         return event.target.nextLink;
//     }
//     else
//     {
//         return '/api/products';
//     }
// }