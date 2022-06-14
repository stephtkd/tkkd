

const initialize = () => {
    let typingTimer;               
    let typeInterval = 500;  
    let searchInput = document.getElementById('searchbox');
    searchInput.value = "";

    searchInput.addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(liveSearch, typeInterval);
    });

    let btnPaidCash = document.getElementById('btn-paid-cash-id');
    btnPaidCash.addEventListener("click", saveEventSubscriptionFnct);

    let allCardIdMemberClass = document.getElementsByClassName('card');

    for (var i = 0; i < allCardIdMemberClass.length; i++) {
        allCardIdMemberClass.item(i).addEventListener("click", seletCardIdMember);
    }
    
}

let cards = document.querySelectorAll('.box')
let listCard = [];
    
function liveSearch() {
    let search_query = document.getElementById("searchbox").value;

    for (var i = 0; i < cards.length; i++) {

        if(cards[i].children[0].textContent.toLowerCase()
                .includes(search_query.toLowerCase())) {
            cards[i].classList.remove("is-hidden");
        } else {
            cards[i].classList.add("is-hidden");
        }
    }
}

function seletCardIdMember(evt){
    let memberId = evt.currentTarget.id.split('card-id-')[1];

    if(evt.target.classList[0] !== "form-select" && 
        evt.target.classList[0] !== "btn-check" && 
        evt.target.classList[0] !== "btn" &&
        evt.target.classList[0] !== "select-option-value"){

        if(evt.currentTarget.style.backgroundColor == "rgb(197, 225, 165)"){//if selected so I delete the selection
            // console.log('------------------------');
            evt.currentTarget.style.backgroundColor = "white";
            json = listCard.find(element => element['member'] == memberId);// get the object with memberId
            id = listCard.indexOf(json); //get index of the object
            listCard.splice(id,1);//delete the object with the index
            console.log(listCard);

        }else{
            // console.log("++++++++++++++++++++++++");
            evt.currentTarget.style.backgroundColor = "#C5E1A5";
            let eventRateEl = document.getElementById('select-event-rate-'+memberId);
            let eventOptionEl = evt.currentTarget.getElementsByClassName('btn-check');
            let arrEventOptionChecked = [];

            for(var i = 0; i< eventOptionEl.length; i++){
                
                if(eventOptionEl[i].checked){
                    arrEventOptionChecked.push(eventOptionEl[i].id.split('member-'+memberId+'-event-')[1]);
                }
            }

            value = {
                    member: memberId,
                    eventRate:eventRateEl.value, 
                    eventOption: arrEventOptionChecked
            };

            listCard.push(value);
            // console.log(listCard);
        }
    }else{ //UPDATE
        //DELETE
        json = listCard.find(element => element['member'] == memberId);// get the object with memberId
        id = listCard.indexOf(json); //get index of the object
        listCard.splice(id,1);//delete the object with the index

        //ADD
        let eventRateEl = document.getElementById('select-event-rate-'+memberId);
        let eventOptionEl = evt.currentTarget.getElementsByClassName('btn-check');
        let arrEventOptionChecked = [];

        for(var i = 0; i< eventOptionEl.length; i++){
            
            if(eventOptionEl[i].checked){
                arrEventOptionChecked.push(eventOptionEl[i].id.split('member-'+memberId+'-event-')[1]);
            }
        }

        value = {
                member: memberId,
                eventRate:eventRateEl.value, 
                eventOption: arrEventOptionChecked
        };

        listCard.push(value);
        // console.log(listCard);
    }

    
}

function saveEventSubscriptionFnct(evt){
    // evt.preventDefault();
    
    let url = evt.currentTarget.dataset.saveEventSubscriptionUrl;
    // console.log(url);
    console.log(listCard);

    const result = $.ajax({
        type: "POST",
        url: url,
        data: {
            'output': listCard
        },
    }).done(function( msg,evt) {
        console.log(msg);
        console.log(evt);
    });
    
}






document.addEventListener("DOMContentLoaded", initialize);
