mw.loader.using('mediawiki.util', function () {

    function addDocwareForm() {
        var formHtml = '<form id="docware-form" action="/custom-api-call" method="POST">' +
            '<label for="docName">Document name:</label>' +
            '<input type="text" name="docName" id="docName" />' +
            '<input type="submit" value="Submit" />' +
            '</form>';
        
        $('#content').append(formHtml);
    }

    // Form submission handling
    $(document).on('submit', '#docware-form', function (e) {
        e.preventDefault();

        var docName = $('#docName').val();

        $.post(mw.util.wikiScript('api'), {
            action: 'docwareformsubmit',
            format: 'json',
            docName: docName
        }, function (data) {
            if (data && data.customformsubmit && data.customformsubmit.status === 'success') {
                alert(data.customformsubmit.message);
            }
            else {
                alert('An error occurred while submitting the form.');
            }
        });
    });

    $(document).ready(function() {
        addDocwareForm();
    });

});