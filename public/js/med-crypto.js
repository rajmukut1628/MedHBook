window.MedCrypto = {
    async encrypt(file, password) {
        // simple demo encryption (later upgrade to AES)
        const text = await file.arrayBuffer();
        return btoa(
            String.fromCharCode(...new Uint8Array(text))
        );
    },

    async decrypt(data, password) {
        const binary = atob(data);
        const bytes = new Uint8Array(binary.length);

        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }

        return bytes;
    }
};