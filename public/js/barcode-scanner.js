import Quagga from "quagga";

document.addEventListener("livewire:load", () => {
    Quagga.init(
        {
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('input[type="text"]'),
                constraints: {
                    facingMode: "environment",
                },
            },
            decoder: {
                readers: ["ean_reader"],
            },
        },
        (err) => {
            if (err) {
                console.error(err);
                return;
            }

            Quagga.start();

            Quagga.onDetected((result) => {
                Livewire.emit("barcodeScanned", result.codeResult.code);
            });
        }
    );
});
