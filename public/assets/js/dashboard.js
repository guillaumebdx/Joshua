const delayToRefresh = 5000;

const storyteller = new StoryTeller(delayToRefresh);
const consoleOpeners = document.getElementsByClassName('console-opener');
for (let i=0; i<consoleOpeners.length; i++) {
    consoleOpeners[i].addEventListener('click', (e)=>{
        e.preventDefault();
        let contest = e.target.dataset.contest;
        let EndDate = e.target.dataset.end;
        storyteller.setUrl(contest);
        storyteller.show(EndDate);
    })
}

const storyCloser = document.getElementById('console-closer');
storyCloser.addEventListener('click', (e)=> {
    e.preventDefault();
    storyteller.hide();
});

const challengeSelector = document.getElementById('challenge-list');
const challengeEditBtn = document.getElementById('edit-challenge-btn');
challengeSelector.addEventListener('change', (e) => {
    challengeEditBtn.setAttribute('href', '/admin/editchallenge/' + e.target.value);
});

const contestSelector = document.getElementById('contest-list');
const contestEditBtn = document.getElementById('edit-contest-btn');
contestSelector.addEventListener('change', (e) => {
    contestEditBtn.setAttribute('href', '/admin/editcontest/' + e.target.value);
});