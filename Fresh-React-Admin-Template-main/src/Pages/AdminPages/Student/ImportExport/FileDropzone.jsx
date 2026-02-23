import { useCallback, useRef, useState } from "react";
import * as XLSX from "xlsx";

function humanFileSize(bytes) {
    if (bytes === 0) return "0 B";
    const units = ["B", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${units[i]}`;
}

export default function FileDropzone({
    uploadUrl = null,
    name = "file",
    accept = ".xlsx,.xls",
    onUploadComplete = () => { },
    setParsedData,
    file,
    setFile,
}) {
    const inputRef = useRef(null);
    const [isDragActive, setDragActive] = useState(false);

    const uid = () => `${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;

    const parseExcelToJson = async (fileObj) => {
        const buffer = await fileObj.arrayBuffer();
        const workbook = XLSX.read(buffer, { type: "array" });

        const sheetName = workbook.SheetNames[0];
        const sheet = workbook.Sheets[sheetName];

        const json = XLSX.utils.sheet_to_json(sheet, {
            defval: "",
            raw: false
        });

        setParsedData(json);
    };

    const addFile = useCallback(
        async (fileObj) => {
            await parseExcelToJson(fileObj);

            const newFile = {
                id: uid(),
                file: fileObj,
                size: humanFileSize(fileObj.size),
                progress: 0,
                status: "queued",
                xhr: null,
            };

            setFile(newFile);
            setTimeout(() => startUpload(newFile), 50);
        },
        [setFile, setParsedData]
    );

    const startUpload = useCallback(
        (item) => {
            setFile((prev) => ({ ...prev, status: "uploading" }));

            const uploadProcess = () => {
                if (!uploadUrl) {
                    simulateUpload();
                    return;
                }

                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append(name, item.file);

                xhr.upload.addEventListener("progress", (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        setFile((prev) => ({ ...prev, progress: percent }));
                    }
                });

                xhr.onload = () => {
                    const success = xhr.status >= 200 && xhr.status < 300;
                    setFile((prev) => ({
                        ...prev,
                        status: success ? "done" : "error",
                        progress: success ? 100 : prev.progress,
                        success,
                    }));
                    onUploadComplete({ id: item.id, success });
                };

                xhr.onerror = () => setFile((prev) => ({ ...prev, status: "error" }));

                xhr.open("POST", uploadUrl);
                xhr.send(formData);

                setFile((prev) => ({ ...prev, xhr }));
            };

            uploadProcess();
        },
        [uploadUrl, name, onUploadComplete, setFile]
    );

    const simulateUpload = () => {
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.floor(Math.random() * 50) + 7;
            if (progress >= 100) progress = 100;

            setFile((prev) => ({
                ...prev,
                progress,
                status: progress === 100 ? "done" : "uploading",
            }));

            if (progress >= 100) clearInterval(interval);
        }, 300);

        setFile((prev) => ({ ...prev, xhr: interval }));
    };

    const onDrop = (e) => {
        e.preventDefault();
        setDragActive(false);

        const dropped = e.dataTransfer.files;
        if (dropped?.length) {
            const fileObj = dropped[0];
            const ok = /\.(xlsx|xls)$/i.test(fileObj.name);
            if (!ok) return;
            addFile(fileObj);
        }
    };

    const onFileInputChange = (e) => {
        const fileObj = e.target.files?.[0];
        if (fileObj) {
            const ok = /\.(xlsx|xls)$/i.test(fileObj.name);
            if (ok) addFile(fileObj);
        }
        e.target.value = "";
    };

    const removeFile = () => {
        setFile(null);
        setParsedData(null);
    };

    return (
        <div className="w-full">
            {file ? (
                <div className="border border-accent rounded p-5">
                    <div className="flex justify-between items-center">
                        <div>
                            <p className="font-medium">{file.file.name}</p>
                            <p className="text-sm text-gray-500">
                                {file.size} • {file.status} • {file.progress}%
                            </p>
                        </div>

                        <button className="text-error cursor-pointer font-semibold" onClick={removeFile}>
                            Remove
                        </button>
                    </div>

                    <div className="w-full bg-gray-200 rounded mt-3 h-2">
                        <div
                            className={`h-2 rounded transition-all ${file?.success || file?.progress === 100 ? "bg-green-600" : "bg-blue-600"
                                }`}
                            style={{ width: `${file.progress}%` }}
                        />
                    </div>
                </div>
            ) : (
                <div
                    className={`border-2 border-dashed rounded-md p-10 text-center transition ${isDragActive ? "border-primary bg-blue-50" : "border-gray-300"} cursor-pointer bg-base-200`}
                    onClick={() => inputRef.current?.click()}
                    onDragOver={(e) => {
                        e.preventDefault();
                        setDragActive(true);
                    }}
                    onDragLeave={(e) => {
                        if (e.target === e.currentTarget) setDragActive(false);
                    }}
                    onDrop={onDrop}
                >
                    <p className="text-gray-600 text-lg">Drop Excel (.xlsx) file here to upload</p>
                    <p className="text-gray-400 mt-2">or</p>
                    <button type="button" className="mt-3 bg-green-600 text-primary-content px-4 py-2 rounded shadow">
                        Select File
                    </button>

                    <input
                        ref={inputRef}
                        type="file"
                        accept={accept}
                        style={{ display: "none" }}
                        onChange={onFileInputChange}
                    />
                </div>
            )}
        </div>
    );
}
