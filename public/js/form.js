// si on met pas parent une écrase ce qu'il y a dans base.hmtl.twig
src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>

    $(document).ready(function(){ // on récupère ici le prototype html créé par Symfony
        //on sélectionne toute la partie de l'input en mettant l'ID
        let $container = $('#livres_keywords');// $container en js convention pour dire que ça contient un objet jquery

        // recupere le nombre d'input keyword
        let index = $container.find(':input').length;// je compte le nombre d'input

        $('.addKeyword').click(function(e) {
            e.preventDefault(); //empêche la soumission du formulaire
            addKeyword($container);
        })

        $('.delete-imgage').click(function (e) {
            $('.responsive-img').remove();
        });

        $('.delete-keyword').click(function (e) {
            let path = $(this).attr('data-delete-path');
            let keywordId = $(this).attr('data-keyword-id');
            let $keywordArea = $(this).closest('.keywordArea');

            $.ajax({
                method: "POST",
                url: path,
                data: {id:keywordId },
                success: function () {
                    $keywordArea.remove();
                },
                error: function () {
                    $('.error-delete-keyword').css('display', 'block');
                }
            })

        });

        if(index==0){ //index c'est le nombre d'input -> si j'ai pas de keyword on en rajoute un
            addKeyword($container); // si index est égal à 0 j'appelle la fonction addkeyword
        }
        // on crée la fonction d'ajout d'un bouton -> créer l'input keyword pour l'index courant
        // et l'ajouté dans la div="car_keywords" avec la méthode append
        function addKeyword($container) {
            let template = $container.attr('data-prototype') //on crée un template en récupérant l'attribut dataprotype qu'on a dans le div
                .replace(/__name__label__/g, 'Mot clé n°' + (index + 1)) //on remplace les name dans l'inspecteur d'éléments par l'index courant
                .replace(/__name__/g, index)
            ;
            let $prototype = $(template);

            $container.append($prototype);

            index ++;
        }
        function deleteButton($prototype) {
            let $deleteLink = $('<a href="#" class="btn waves-effect waves-light red">Annuler</a>');

            $prototype.append($deleteLink);

            $deleteLink.click(function(e) {
                $prototype.remove();

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        }
    });
