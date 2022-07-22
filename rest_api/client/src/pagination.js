import { getData } from './getData.js';
export function pagination(response)
{
    if (typeof response['hydra:view'] == 'undefined') return;
    var nextLink = document.getElementById("next-link");
    var backLink = document.getElementById("back-link");


    if (typeof response['hydra:view']['hydra:next'] !== 'undefined')
    {
        nextLink.addEventListener('click',getData);
        nextLink.style.display = 'block';
        backLink.style.display = 'none';
        nextLink.nextLink = response['hydra:view']['hydra:next'];
    }
    else if (typeof response['hydra:view']['hydra:first'] !== 'undefined')
    {
        backLink.addEventListener('click',getData);
        nextLink.style.display = 'none';
        backLink.style.display = 'block';
        backLink.backLink = response['hydra:view']['hydra:first'];
    }
    else
    {
        nextLink.style.display = 'none';
        backLink.style.display = 'none';
    }
}