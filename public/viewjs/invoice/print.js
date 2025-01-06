
        var code = new QRCode(document.getElementById("qrcode"), {
            text: invoice.qrcode,
            width: 100,
            height: 105,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        window.addEventListener("load", (event) => {
            console.log('aa')
            window.print()
        });