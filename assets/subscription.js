

const initialize = () => {
    let typingTimer;               
    let typeInterval = 500;  
    let searchInput = document.getElementById('searchbox');
    searchInput.value = "";
    let subscriptionId = document.getElementById('subscription-id').value;
    var memberId = "";

    // document.getElementById('member-1-event-1').checked = true;


    searchInput.addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(liveSearch, typeInterval);
    });

    let allCardIdMemberClass = document.getElementsByClassName('card');

    const result = $.ajax({
        type: "GET",
        url: "/subscription/"+subscriptionId+"/getCart",
    }).done(function( msg,evt) {
        // console.log(msg);
        // console.log(evt);

        for (var i = 0; i < allCardIdMemberClass.length; i++) {
            // console.log(result.responseJSON);

            for(var j = 0; j < result.responseJSON[0].length; j++){
                memberId =  allCardIdMemberClass.item(i).id.split('card-id-')[1];
                // console.log(memberId);
                // console.log(allCardIdMemberClass.item(i));
                // console.log(result.responseJSON[0][j]);
                
                if(result.responseJSON[0][j]['member'] == memberId ){
                    allCardIdMemberClass.item(i).style.backgroundColor = "#C5E1A5";

                    let memberId = allCardIdMemberClass.item(i).id.split('card-id-')[1];
                    let eventId = document.getElementById('event-id').value;
                    let eventRateEl = document.getElementById('select-event-rate-'+memberId);
                    let eventOptionEl = allCardIdMemberClass.item(i).getElementsByClassName('btn-check');
                    let arrEventOptionChecked = [];

                    for(var k = 0; k< eventOptionEl.length; k++){
                        
                        if(eventOptionEl[k].checked){
                            arrEventOptionChecked.push(eventOptionEl[k].id.split('member-'+memberId+'-event-')[1]);
                        }
                    }

                    value = {
                        member: memberId,
                        event: eventId,
                        eventRate:eventRateEl.value, 
                        eventOption: arrEventOptionChecked
                    };

                    listCard.push(value);


                    let selectPrice = document.getElementById('select-event-rate-'+memberId);
                    let priceArray = selectPrice.options[selectPrice.selectedIndex].firstChild.data.split('-');
                    let spanTotalPrice = document.getElementById('total-price');
                    let actualTotalPrice = parseInt(spanTotalPrice.firstChild.data);
                    spanTotalPrice.textContent = actualTotalPrice+parseInt(priceArray[1]);
                }
            }
    
            console.log(listCard);
            allCardIdMemberClass.item(i).addEventListener("click", seletCardIdMember);

        }
    });

    
    
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
    evt.preventDefault();
    let memberId = evt.currentTarget.id.split('card-id-')[1];
    let eventId = document.getElementById('event-id').value;
    let subscriptionId = document.getElementById('subscription-id').value;
    console.log( evt.target);
    console.log(evt.target.className);

    if(evt.target.classList[0] !== "form-select" && 
        evt.target.className !== "event-option-class" && 
        // evt.target.classList[0] !== "btn" &&
        evt.target.classList[0] !== "select-option-value"){

        if(evt.currentTarget.style.backgroundColor == "rgb(197, 225, 165)"){//if selected so I delete the selection
            // console.log('------------------------');
            evt.currentTarget.style.backgroundColor = "white";
            json = listCard.find(element => element['member'] == memberId);// get the object with memberId
            id = listCard.indexOf(json); //get index of the object
            listCard.splice(id,1);//delete the object with the index
            console.log(listCard);


            const result = $.ajax({
                type: "POST",
                url: "/subscription/"+subscriptionId+"/addOneCart",
                data: {
                    'output': listCard
                },
            }).done(function( msg,evt) {
                console.log(msg);
                console.log(evt);
            });

            let selectPrice = document.getElementById('select-event-rate-'+memberId);
            let priceArray = selectPrice.options[selectPrice.selectedIndex].firstChild.data.split('-');
            let spanTotalPrice = document.getElementById('total-price');
            let actualTotalPrice = parseInt(spanTotalPrice.firstChild.data);
            spanTotalPrice.textContent = actualTotalPrice-parseInt(priceArray[1]);

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
                    event: eventId,
                    eventRate:eventRateEl.value, 
                    eventOption: arrEventOptionChecked
            };

            listCard.push(value);

            const result = $.ajax({
                type: "POST",
                url: "/subscription/"+subscriptionId+"/addOneCart",
                data: {
                    'output': listCard
                },
            }).done(function( msg,evt) {
                // console.log(msg);
                // console.log(evt);
            });

            console.log(listCard);

            let selectPrice = document.getElementById('select-event-rate-'+memberId);
            let priceArray = selectPrice.options[selectPrice.selectedIndex].firstChild.data.split('-');
            let spanTotalPrice = document.getElementById('total-price');
            let actualTotalPrice = parseInt(spanTotalPrice.firstChild.data);
            spanTotalPrice.textContent = actualTotalPrice+parseInt(priceArray[1]);

            
        }
    }else if(evt.target.className == "event-option-class"){
        console.log(evt.target.checked);
        evt.target.checked = false;
        // document.getElementById(evt.target.id).checked = true;
        console.log('CHECKED');
        console.log(evt.target.checked);
    }else{ //UPDATE
        //DELETE
        console.log(evt.target.classList[0]);
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
        console.log(listCard);
    }

    
}




document.addEventListener("DOMContentLoaded", initialize);
