
function processImage() {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************
        
        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "67c00784797447ce94bfbbf99ea1fce6";
        
        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
        "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
        
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
        
        // Display the image.
        sourceImageUrl = document.getElementById("url_blob").value;
        
        //umpan
        //var sourceImageUrl = $umpan_url;

        document.querySelector("#sourceImage").src = sourceImageUrl;
        
        // Make the REST API call.
        $(document).ready(function(){
            $("button").click(function(){
                $.ajax({

                    url: uriBase + "?" + $.param(params),

            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
            },
            
            type: "POST",
            
            // Request body.
            data: '{"url": ' + '"' + document.getElementById("url_blob").value + '"}',
        })

                .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
        })
                .done(function(data) {

                            // Show formatted JSON on webpage.
                            $("#responseTextArea").val(JSON.stringify(data, null, 2));
                            $("#description").text(data.description.captions[0].text);
                        })

                .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " : errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" : jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
            });
        });
    }
