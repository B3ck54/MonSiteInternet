$(document).ready(function(){ // on récupère ici le prototype html créé par Symfony
        //on sélectionne toute la partie de l'input en mettant l'ID
        let $container = $('#livres_editions');// $container en js convention pour dire que ça contient un objet jquery
        // recupere le nombre d'input Edition
        let index = $container.find(':input').length;// je compte le nombre d'input

        $container.find('label.required').remove(); // supprime le label des Editions (i++)

        $('.addEdition').click(function(e) {
            e.preventDefault(); //empêche la soumission du formulaire
            addEdition($container);
        })

      
        $('.delete-edition').click(function (e) {
            let path = $(this).attr('data-delete-path');
            let editionId = $(this).attr('data-edition-id');
            let $editionArea = $(this).closest('.editionArea');

            $.ajax({
                method: "POST",
                url: path,
                data: {id:editionId },
                success: function () {
                    $editionArea.remove();
                },
                error: function () {
                    $('.error-delete-edition').css('display', 'block');
                }
            })

        });

        if(index==0){ //index c'est le nombre d'input -> si j'ai pas de edition on en rajoute un
            addEdition($container); // si index est égal à 0 j'appelle la fonction addedition
        }
        // on crée la fonction d'ajout d'un bouton -> créer l'input edition pour l'index courant
        // et l'ajouté dans la div="livre_editions" avec la méthode append
        function addEdition($container) {
            let template = $container.attr('data-prototype') //on crée un template en récupérant l'attribut dataprotype qu'on a dans le div
                .replace(/__name__label__/g, '') //on remplace les name dans l'inspecteur d'éléments par l'index courant
                .replace(/__name__/g, index)
            ;
            let $prototype = $(template);

            deleteButton($prototype);


            $container.append($prototype);

            index ++;
        }

        function deleteButton($prototype) {
            let $deleteLink = $('');

            $prototype.append($deleteLink);

            $deleteLink.click(function(e) {
                $prototype.remove();

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        } 
    });

