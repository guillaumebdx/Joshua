const contestSwitch = document.getElementsByClassName('manage-contest-display')
for (let i=0; i < contestSwitch.length; i++) {
    contestSwitch[i].addEventListener('click', (event) => {
        fetch('/admin/displaycontest', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'visible' : event.target.dataset.visible,
                'id'      : event.target.dataset.id
            })
        })
            //.then(response => response.json())
            //.then(data => console.log(data))
    })
}
