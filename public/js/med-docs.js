window.MedDocs = {

    async uploadDocument(form, password) {
        const fileInput = form.querySelector('#documentFileInput');
        const file = fileInput.files[0];

        if (!file) {
            alert("Select file first");
            return;
        }

        // encrypt
        const encrypted = await MedCrypto.encrypt(file, password);

        const formData = new FormData(form);
        formData.append('encrypted_file', encrypted);

        try {
            const res = await fetch('/medical-documents', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });

            const data = await res.json();

            if (data.success) {
                alert("Uploaded successfully");
                window.dispatchEvent(new CustomEvent('med-doc-uploaded', { detail: data.document }));
            }

        } catch (e) {
            alert("Upload failed");
        }
    },

    async viewDocument(id, password) {
        const res = await fetch(`/medical-documents/${id}`);
        const data = await res.json();

        const decrypted = await MedCrypto.decrypt(data.encrypted_file, password);

        const blob = new Blob([decrypted]);
        const url = URL.createObjectURL(blob);

        window.open(url);
    },

    async downloadDocument(id, password) {
        const res = await fetch(`/medical-documents/${id}`);
        const data = await res.json();

        const decrypted = await MedCrypto.decrypt(data.encrypted_file, password);

        const blob = new Blob([decrypted]);
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = data.filename;
        a.click();
    },

    async deleteDocument(id) {
        if (!confirm("Delete?")) return;

        await fetch(`/medical-documents/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        window.dispatchEvent(new CustomEvent('med-doc-deleted', { detail: { id } }));
    },

    async promptPassword() {
        return prompt("Enter your password:");
    }
};