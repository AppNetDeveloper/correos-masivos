<script>

	function extractmail(){
		var text = document.getElementById("emailestr").value;
		function extractEmails (text)
        {
            return text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
        }

		const textarea = document.getElementById('emailestr');

	textarea.value = extractEmails(text).join('\n');


	//let text = "How are you doing today today today today B ?";
	//const myArray = text.split(" ");
	var myArray = extractEmails(text);
    seen = myArray.filter((s => v => s.has(v) || !s.add(v))(new Set));
   // document.getElementById("demo2").innerHTML = seen; 
   if (seen === undefined || seen.length == 0) {
			//alert("sin duplicado")

			textarea.value = extractEmails(text).join(';');

			sendmass()
		}else{
			Swal.fire({
				title: 'Tienes 1 o varios correos duplicados!',
				showDenyButton: true,
				showCancelButton: true,
				confirmButtonText: 'Borrar duplicados!',
				denyButtonText: `Dejarlo asi!`,
				}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
				//	Swal.fire('Saved!', '', 'success')
				borrdup(extractEmails(text));
				} else if (result.isDenied) {
				//	Swal.fire('Changes are not saved', '', 'info')
				textarea.value = extractEmails(text).join(';');

				sendmass()
				}
				})
		}

	}
function borrdup(a){
	/// string final si duplicados.
	var lines =a;
	var uniqueNames = [];

	for(var i = 0; i < lines.length; i++)
	{
		if(uniqueNames.indexOf(lines[i]) == -1)
			uniqueNames.push(lines[i]);
	}
	if(uniqueNames.indexOf(uniqueNames[uniqueNames.length-1])!= -1)
		uniqueNames.pop();
	for(var i = 0; i < uniqueNames.length; i++)
	{
	//	document.write(uniqueNames[i]);
	//	document.write("<br/>");
	const textarea = document.getElementById('emailestr');
	//textarea.value = uniqueNames.join("\n");
	textarea.value = uniqueNames.join(";");
	sendmass()
	}
}

function sendmass(){

var l = document.getElementById("emailestr").value;
var a = document.getElementById("asunto").value;
var c = document.getElementById("contenido").value;
var e = document.getElementById("email").value;
var u = document.getElementById("usmtp").value;
var s = document.getElementById("smtp").value;
var p = document.getElementById("passwordsmtp").value;


$.ajax({
				type: "POST",
				url: "correomasivo-send.php" ,
				data:{"emaillist":l, "asunto":a, "contenido":c, "email":e, "user":u, "smtp":s, "password":p},
				cache: false,
					beforeSend: function() {
					
					},
					success: function(data) {
						alert(data);
						
					},
					error: function(data) {
						alert("Error!"+data);
						
					}

			});

}
</script>
