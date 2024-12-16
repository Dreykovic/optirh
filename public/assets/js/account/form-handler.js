function handleSubmit(e,url, method, form_id, message) {
    e.preventDefault();
    const form = document.getElementById(form_id);
    const formData = new FormData(form); // Récupère les données du formulaire
 
    fetch(url, {
       method: method,
       body: formData
    })
    .then(response => {
       if (response.ok) {
          Swal.fire({
             title: "Bon travail 👍️!",
             text: message,
             icon: "success"
          });
          form.reset(); // Réinitialise le formulaire
       } else {
          Swal.fire({
             icon: "error",
             title: "Oops...🥵️",
             text: "Une erreur s'est produite !",
          });
       }
    })
    .catch(error => {
       Swal.fire({
          title: "The Internet?",
          text: "That thing is still around?",
          icon: "question"
       });
       alert('Erreur réseau ou serveur : ' + error.message);
    });
 }
 