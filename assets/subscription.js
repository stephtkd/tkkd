
const initialize = () => {
    let typingTimer;               
    let typeInterval = 500;  
    let searchInput = document.getElementById('searchbox');
    searchInput.value = "";

    searchInput.addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(liveSearch, typeInterval);
    });

    let allBtnSaveEventSubscriptionClass = document.getElementsByClassName('btn-save-event-subscription-class');

    for (var i = 0; i < allBtnSaveEventSubscriptionClass.length; i++) {
        allBtnSaveEventSubscriptionClass.item(i).addEventListener("click", saveEventSubscriptionFnct);
    }
    
}

let cards = document.querySelectorAll('.box')
    
function liveSearch() {
    let search_query = document.getElementById("searchbox").value;

    for (var i = 0; i < cards.length; i++) {
        if(cards[i].textContent.toLowerCase()
                .includes(search_query.toLowerCase())) {
            cards[i].classList.remove("is-hidden");
        } else {
            cards[i].classList.add("is-hidden");
        }
    }
}

function saveEventSubscriptionFnct(evt){
    let url = evt.currentTarget.dataset.saveEventSubscriptionUrl;
    let eventRateEl = document.getElementById('select-event-rate');
    let member = evt.currentTarget;
    console.log(member);


    let dataValues = {
        eventRate: eventRateEl.value,
        eventOption: 'test' 
    };

    $.ajax({
        type: "POST",
        url: url,
        data: {
            'output': dataValues
        },
    });
}









document.addEventListener("DOMContentLoaded", initialize);
