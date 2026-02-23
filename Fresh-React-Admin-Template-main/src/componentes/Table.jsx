import { useEffect, useMemo, useRef, useState } from "react";
import { BsThreeDotsVertical } from "react-icons/bs";
import { FiFilter } from "react-icons/fi";
import { GoPlusCircle } from "react-icons/go";
import { GrDocumentUpdate } from "react-icons/gr";
import { IoIosArrowDown, IoMdClose } from "react-icons/io";
import { MdBlock, MdDeleteOutline } from "react-icons/md";
import { RiResetLeftFill } from "react-icons/ri";
import { toast } from "react-toastify";
import Loader from "./Loader";
import UseAuth from "../Hooks/UseAuth";
import { Translations } from "../utils/Translations";

// Static data for classes
const staticClassData = [
    { id: 1, name: "Class A", status: 1, updated_at: "2023-10-01 10:00:00" },
    { id: 2, name: "Class B", status: 0, updated_at: "2023-10-02 11:00:00" },
    { id: 3, name: "Class C", status: 1, updated_at: "2023-10-03 12:00:00" },
    { id: 4, name: "Class D", status: 0, updated_at: "2023-10-04 13:00:00" },
    { id: 5, name: "Class E", status: 1, updated_at: "2023-10-05 14:00:00" },
];

const Table = () => {
    const { language } = UseAuth();
    const t = Translations[language];
    const items = [t.name, t.status, t.date, t.action];
    const [openUpdate, setOpenUpdate] = useState(false);
    const [openAdd, setOpenAdd] = useState(false);
    const [openFilter, setOpenFilter] = useState(false);
    const [popOpen, setPopOpen] = useState(null);
    const [popoverPosition, setPopoverPosition] = useState({ top: 0, left: 0 });
    const [rows, setRows] = useState(staticClassData);
    const [currentPage, setCurrentPage] = useState(1);
    const [openPageSize, setOpenPageSize] = useState(false);
    const [pageSizeValue, setPageSizeValue] = useState("10");
    const pageSize = parseInt(pageSizeValue, 10) || 10;
    const pageSizeOptions = ["10", "20", "50", "100"];
    const [searchTerm, setSearchTerm] = useState("");
    const [editingRow, setEditingRow] = useState(null);
    const [nameValue, setNameValue] = useState(null);
    const [updateName, setUpdateName] = useState("");
    const [loading, setLoading] = useState(false);
    const [checkedItems, setCheckedItems] = useState(items.map(() => true));
    const [search, setSearch] = useState(true);
    const [panelHide, setPanelHide] = useState(false);
    const menuRef = useRef(null);
    const actionButtonRefs = useRef({});

    const col = {
        name: checkedItems[0],
        status: checkedItems[1],
        datetime: checkedItems[2],
        actions: checkedItems[3],
    };

    // Close popover on outside click, escape, or scroll
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (menuRef.current && !menuRef.current.contains(e.target)) {
                setPopOpen(null);
            }
        };
        const handleEscape = (e) => {
            if (e.key === "Escape") setPopOpen(null);
        };
        const handleScroll = () => setPopOpen(null);

        document.addEventListener("mousedown", handleClickOutside);
        document.addEventListener("keydown", handleEscape);
        window.addEventListener("scroll", handleScroll, true);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
            document.removeEventListener("keydown", handleEscape);
            window.removeEventListener("scroll", handleScroll, true);
        };
    }, []);

    const toggleChecked = (index) => {
        setCheckedItems((prev) => prev.map((val, i) => (i === index ? !val : val)));
    };

    const filteredRows = useMemo(() => {
        const term = searchTerm.trim().toLowerCase();
        if (!term) return rows;
        return rows.filter((row) =>
            ["name"].some((field) =>
                String(row[field] || "").toLowerCase().includes(term)
            )
        );
    }, [rows, searchTerm]);

    // prevent form reload
    const handleSearchSubmit = (e) => {
        e.preventDefault();
        setSearch(!search);
        if (search) {
            setSearchTerm(e.target.search.value);
        } else {
            setSearchTerm("");
            e.target.reset();
        }
    };
    const handleSelectPageSize = (v) => {
        setPageSizeValue(v);
        setOpenPageSize(false);
        setCurrentPage(1);
    };

    // pagination
    const totalPages = Math.max(1, Math.ceil(filteredRows?.length / pageSize));
    const currentPageSafe = Math.min(currentPage, totalPages);
    const startIndex = (currentPageSafe - 1) * pageSize;
    const currentRows = filteredRows?.slice(startIndex, startIndex + pageSize);

    const handlePageChange = (page) => {
        if (page >= 1 && page <= totalPages) {
            setCurrentPage(page);
            setPopOpen(null);
        }
    };

    // Position and toggle popover for given row id
    const handleThreeDotsClick = (rowId, e) => {
        e.stopPropagation();
        const buttonElement = actionButtonRefs.current[rowId];
        if (buttonElement) {
            const rect = buttonElement.getBoundingClientRect();
            let top = rect.bottom;
            let left = rect.left - 145;
            const viewportHeight = window.innerHeight;
            const popoverApproxHeight = viewportHeight / 4;
            if (top + popoverApproxHeight > viewportHeight) {
                top = rect.top - 120;
            }
            const popoverApproxWidth = 180;
            if (left + popoverApproxWidth > window.innerWidth) {
                left = window.innerWidth - popoverApproxWidth - 75;
            }
            setPopoverPosition({ top, left });
        }
        setPopOpen((prev) => (prev === rowId ? null : rowId));
    };

    // Actions from popover (id-based)
    const handleActionClick = (action, rowId) => {
        const row = rows.find((r) => r.id === rowId);
        if (action === "update") {
            setEditingRow(row);
            setUpdateName(row.name || "");
            setOpenUpdate(true);
            setPopOpen(null);
            return;
        }
        setPopOpen(null);
    };

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        setOpenFilter(false);
    };

    const handleFilterReset = () => {
        setCheckedItems(items.map(() => true));
    };

    const reset = () => {
        setNameValue(null);
        setUpdateName("");
    };
    const closePanel = (setter, extraCleanup) => {
        setPanelHide(true);
        setTimeout(() => {
            setter(false);
            extraCleanup?.();
            setPanelHide(false);
        }, 300);
    };


    // Create Class (using static data)
    const handleCreateSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);

        // Simulate API delay
        setTimeout(() => {
            const newId = Math.max(...rows.map(r => r.id)) + 1; // Generate new ID
            const newClass = {
                id: newId,
                name: nameValue,
                status: 1, // Default to active
                updated_at: new Date().toISOString().slice(0, 19).replace('T', ' '), // Current timestamp
            };
            setRows(prev => [...prev, newClass]);
            toast.success("Class added successfully");
            reset();
            setLoading(false);
            setOpenAdd(false);
        }, 500); // Simulate loading
    };

    // Update class (using static data)
    const handleUpdateSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);

        // Simulate API delay
        setTimeout(() => {
            setRows(prev => prev.map(row =>
                row.id === editingRow.id
                    ? { ...row, name: updateName, updated_at: new Date().toISOString().slice(0, 19).replace('T', ' ') }
                    : row
            ));
            toast.success("Class updated successfully");
            setLoading(false);
            setOpenUpdate(false);
            setEditingRow(null);
        }, 500); // Simulate loading
    };

    // Handle Status Class (using static data)
    const handleStatusChange = async (id) => {
        setLoading(true);

        // Simulate API delay
        setTimeout(() => {
            setRows(prev => prev.map(row =>
                row.id === id
                    ? { ...row, status: row.status === 1 ? 0 : 1, updated_at: new Date().toISOString().slice(0, 19).replace('T', ' ') }
                    : row
            ));
            toast.success("Status changed successfully");
            setLoading(false);
            setPopOpen(null);
        }, 500); // Simulate loading
    };

    // Delete Class (using static data)
    const handleDelete = async (id) => {
        setLoading(true);

        // Simulate API delay
        setTimeout(() => {
            setRows(prev => prev.filter(row => row.id !== id));
            toast.success("Class deleted successfully");
            setLoading(false);
            setPopOpen(null);
        }, 500); // Simulate loading
    };

    return (
        <div className="relative w-full">
            <div className={`p-5 ${popOpen ? "pb-16" : ""}`}>
                <div className="w-full px-5 relative bg-base-200">
                    <div className="w-full py-3 text-primary-content flex flex-col lg:flex-row items-center justify-between gap-2 lg:gap-5">
                        <h1 className="font-semibold text-3xl">{t.table}</h1>

                        <div className="flex flex-col lg:flex-row lg:items-center justify-end gap-2">
                            {/* SEARCH */}
                            <form onSubmit={handleSearchSubmit} className="flex items-center">
                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    placeholder={t.search}
                                    disabled={!search}
                                    className={`border-2 border-primary w-full h-10 px-2 focus:outline-none rounded-s text-primary-content ${!search ? "bg-gray-200 cursor-not-allowed opacity-70" : ""}`}
                                />
                                <button
                                    type="submit"
                                    className="flex items-center gap-1 font-bold text-white bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded-e cursor-pointer "
                                >
                                    {search ? t.search : t.reset}
                                </button>
                            </form>

                            <div className="flex items-center lg:justify-end gap-2">
                                {/* PAGE SIZE */}
                                <div className="relative w-full lg:w-16 ">
                                    <button
                                        onClick={() => setOpenPageSize(!openPageSize)}
                                        className="w-full py-1.5 border-2 border-primary bg-base-200 rounded cursor-pointer flex items-center justify-center gap-2"
                                        type="button"
                                    >
                                        {pageSizeValue} <IoIosArrowDown className={`transition-transform duration-300 ${openPageSize ? "-rotate-180" : ""}`} />
                                    </button>

                                    <div
                                        className={`absolute z-20 w-full mt-1 bg-base-200 border-2 border-primary rounded shadow overflow-hidden transition-all duration-200 origin-top ${openPageSize ? "opacity-100 scale-y-100" : "opacity-0 scale-y-0"
                                            }`}
                                    >
                                        {pageSizeOptions.map((opt) => (
                                            <div key={opt} onClick={() => handleSelectPageSize(opt)} className="text-center py-2 hover:bg-gray-100 cursor-pointer">
                                                {opt}
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* FILTER */}
                                <button onClick={() => setOpenFilter(true)} className="flex items-center gap-1 font-bold text-primary bg-primary-light hover:bg-primary-light-hover px-3 py-2 text-base rounded cursor-pointer">
                                    <FiFilter />
                                    {t.filter}
                                </button>

                                {/* ADD */}
                                <button onClick={() => setOpenAdd(true)} className="flex items-center gap-1 font-bold text-white bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded cursor-pointer whitespace-nowrap">
                                    <GoPlusCircle />
                                    {t.add}
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* TABLE */}
                    <div className="w-full overflow-x-auto">
                        <table className="border-separate border-spacing-0 rounded-md w-full">
                            <thead className="h-10">
                                <tr className="uppercase text-center bg-base-100 text-primary-content font-medium text-base">
                                    {col.name && <th className="border border-accent px-4 py-2 whitespace-nowrap">{t.name}</th>}
                                    {col.status && <th className="border border-accent px-4 py-2 whitespace-nowrap">{t.status}</th>}
                                    {col.datetime && <th className="border border-accent px-4 py-2 whitespace-nowrap">{t.date}</th>}
                                    {col.actions && <th className="border border-accent px-4 py-2 whitespace-nowrap">{t.action}</th>}
                                </tr>
                            </thead>

                            <tbody className="text-center text-primary-content relative text-sm">
                                {currentRows?.length === 0 ? (
                                    <tr>
                                        <td colSpan={4} className="border border-accent py-4 px-4 whitespace-nowrap text-center">
                                            {searchTerm ? `No data found for "${searchTerm}".` : "No data available."}
                                        </td>
                                    </tr>
                                ) : (
                                    currentRows?.map((row) => {
                                        return (
                                            <tr key={row.id}>
                                                {col.name && <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.name}</td>}
                                                {col.status && <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.status === 1 ? "Active" : "Inactive"}</td>}
                                                {col.datetime && <td className="border border-accent py-2 px-4 whitespace-nowrap">{row?.updated_at}</td>}
                                                {col.actions && (
                                                    <td className="border border-accent relative whitespace-nowrap">
                                                        <button
                                                            ref={(el) => (actionButtonRefs.current[row?.id] = el)}
                                                            onClick={(e) => handleThreeDotsClick(row?.id, e)}
                                                            className="border-none py-0 px-2"
                                                            type="button"
                                                            aria-label={`Actions for ${row?.name}`}
                                                        >
                                                            <BsThreeDotsVertical className="text-xl cursor-pointer font-bold" />
                                                        </button>
                                                    </td>
                                                )}
                                            </tr>
                                        );
                                    })
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Global Popover */}
                    {popOpen && (
                        <div
                            ref={menuRef}
                            className="fixed min-w-40 bg-base-200 font-bold shadow-lg rounded border border-accent p-2 z-50"
                            style={{
                                top: `${popoverPosition.top}px`,
                                left: `${popoverPosition.left}px`,
                            }}
                            role="menu"
                            aria-orientation="vertical"
                        >
                            <button
                                onClick={() => handleStatusChange(popOpen)}
                                className="flex items-center gap-2 p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                                role="menuitem"
                                disabled={loading}
                            >
                                <MdBlock size={20} />
                                {t.active}
                            </button>

                            <button
                                onClick={() => handleActionClick("update", popOpen)}
                                className="flex items-center gap-2 p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                                role="menuitem"
                            >
                                <GrDocumentUpdate size={18} />{t.update}
                            </button>

                            <button
                                onClick={() => handleDelete(popOpen)}
                                className="flex items-center gap-2 p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                                role="menuitem"
                                disabled={loading}
                            >
                                <MdDeleteOutline size={20} /> {t.delete}
                            </button>
                        </div>
                    )}

                    {/* PAGINATION */}
                    <div className="py-3 lg:flex items-center justify-between space-y-6 lg:space-y-0">
                        <div className="text-sm lg:text-base lg:text-left text-center w-full">
                            {t.showing}
                            <span>
                                {Math.min(startIndex + currentRows.length === 0 || rows.length === 0 ? 0 : startIndex + 1, rows.length)}
                            </span>{" "}
                            {t.to} <span>{Math.min(startIndex + currentRows.length, rows.length)}</span> of{" "}
                            <span>{rows.length}</span> {t.entries}
                        </div>

                        <div className="w-full flex lg:justify-end justify-center items-center gap-2 flex-wrap">
                            <button
                                onClick={() => handlePageChange(currentPageSafe - 1)}
                                disabled={currentPageSafe === 1}
                                className="px-3 py-1 border border-accent bg-base-200 cursor-pointer hover:bg-primary hover:text-neutral-content rounded disabled:opacity-50"
                            >
                                {t.prev}
                            </button>

                            <button
                                onClick={() => handlePageChange(1)}
                                className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${currentPageSafe === 1 ? "bg-primary text-neutral-content" : ""
                                    }`}
                            >
                                1
                            </button>

                            {currentPageSafe > 3 && <span className="px-2">...</span>}

                            {Array.from({ length: totalPages })
                                .map((_, i) => i + 1)
                                .filter((page) => page !== 1 && page !== totalPages && Math.abs(page - currentPageSafe) <= 1)
                                .map((page) => (
                                    <button
                                        key={page}
                                        onClick={() => handlePageChange(page)}
                                        className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${page === currentPageSafe ? "bg-primary text-neutral-content" : ""
                                            }`}
                                    >
                                        {page}
                                    </button>
                                ))}

                            {currentPageSafe < totalPages - 2 && <span className="px-2">...</span>}

                            {totalPages > 1 && (
                                <button
                                    onClick={() => handlePageChange(totalPages)}
                                    className={`px-3 py-1 border bg-base-200 border-accent hover:bg-secondary ${currentPageSafe === totalPages ? "bg-primary text-neutral-content" : ""
                                        }`}
                                >
                                    {totalPages}
                                </button>
                            )}

                            <button
                                onClick={() => handlePageChange(currentPageSafe + 1)}
                                disabled={currentPageSafe === totalPages}
                                className="px-3 py-1 border border-accent bg-base-200 hover:bg-primary hover:text-neutral-content cursor-pointer disabled:opacity-50"
                            >
                                {t.next}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Create Class */}
            {openAdd && (
                <div
                    className={`fixed top-0 right-0 max-w-xl w-full bg-base-200 h-screen overflow-y-scroll p-5 z-50
                    transform transition-transform duration-300
                    ${panelHide ? "translate-x-full" : "translate-x-0"}`}
                >
                    <div className="flex items-center justify-between border-b-2 border-accent pb-2">
                        <h1 className="text-base sm:text-lg font-semibold">{t.class}</h1>
                        <button type="button" onClick={() => { reset(); setOpenAdd(false) }} className="text-sm text-primary-content cursor-pointer hover:text-error">
                            <IoMdClose size={24} />
                        </button>
                    </div>

                    <form className="mt-4 space-y-4" onSubmit={handleCreateSubmit}>
                        <div>
                            <label className="pb-1 font-semibold">{t.name} <span className="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="name"
                                placeholder={t.name}
                                value={nameValue || ""}
                                onChange={(e) => setNameValue(e.target.value)}
                                className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                                required
                            />
                        </div>

                        <div className="flex items-center justify-end gap-5">
                            <button type="button" className="bg-secondary text-primary-content py-2 px-4 rounded-md  cursor-pointer " onClick={() => { reset(); setOpenAdd(false) }}>{t.cancel}</button>
                            <button type="submit" className="bg-primary text-primary-content py-2 px-4 rounded-md  cursor-pointer ">{t.add}</button>
                        </div>
                    </form>
                </div>
            )}

            {/* FILTER SIDE PANEL */}
            {openFilter && (
                <div
                    className={`fixed top-0 right-0 max-w-xl w-full bg-base-200 h-screen overflow-y-scroll z-40
                    transform transition-transform duration-300
                    ${panelHide ? "translate-x-full" : "translate-x-0"}`}
                >
                    <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
                        <h1 className="text-base sm:text-lg font-semibold">{t.filter}</h1>
                        <div className="flex items-center gap-5">
                            <button type="button" onClick={handleFilterReset} className="text-sm text-primary-content cursor-pointer hover:text-error"><RiResetLeftFill size={24} /></button>
                            <button type="button" onClick={() => setOpenFilter(false)} className="text-sm text-primary-content cursor-pointer hover:text-error"><IoMdClose size={24} /></button>
                        </div>
                    </div>

                    <form className="mt-4 px-5 space-y-2" onSubmit={handleFilterSubmit}>
                        {items.map((label, index) => (
                            <button
                                key={index}
                                type="button"
                                onClick={() => toggleChecked(index)}
                                className="w-full flex items-center gap-2 px-2 py-2 rounded-md hover:bg-accent/60 text-left"
                            >
                                <input
                                    type="checkbox"
                                    className="checkbox"
                                    checked={!!checkedItems[index]}
                                    onClick={() => toggleChecked(index)}
                                    onChange={(e) => {
                                        e.stopPropagation();
                                        toggleChecked(index);
                                    }}
                                    aria-label={`Toggle column ${label}`}
                                />
                                <span>{label}</span>
                            </button>
                        ))}

                        <div className="flex items-center justify-end gap-5">
                            <button type="button" className="bg-secondary text-primary-content py-2 px-4 rounded-md" onClick={() => setOpenFilter(false)}>{t.cancel}</button>
                            <button type="submit" className="bg-primary text-primary-content py-2 px-4 rounded-md">{t.apply}</button>
                        </div>
                    </form>
                </div>)}

            {/* UPDATE SIDE PANEL */}
            {openUpdate && editingRow && (
                <div
                    className={`fixed top-0 right-0 max-w-xl w-full bg-base-200 h-screen overflow-y-scroll p-5 z-50
                    transform transition-transform duration-300
                    ${panelHide ? "translate-x-full" : "translate-x-0"}`}
                >
                    <div className="flex items-center justify-between border-b-2 border-accent pb-2">
                        <h1 className="text-base sm:text-lg font-semibold">{t.update}</h1>
                        <button type="button" onClick={() => { setOpenUpdate(false); setEditingRow(null); }} className="text-sm text-primary-content cursor-pointer hover:text-error">
                            <IoMdClose size={24} />
                        </button>
                    </div>

                    <form className="mt-4 space-y-4" onSubmit={handleUpdateSubmit}>
                        <div>
                            <label className="pb-1 font-semibold">{t.name} <span className="text-red-500">*</span></label>
                            <input type="text" value={updateName} onChange={(e) => setUpdateName(e.target.value)} className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary" required />
                        </div>

                        <div className="flex items-center justify-end gap-5">
                            <button type="button" className="bg-secondary text-primary-content py-2 px-4 rounded-md cursor-pointer" onClick={() => { setOpenUpdate(false); setEditingRow(null); }}>{t.cancel}</button>
                            <button type="submit" className="bg-primary text-primary-content py-2 px-4 rounded-md cursor-pointer">{t.update}</button>
                        </div>
                    </form>
                </div>
            )}
        </div>
    );
};

export default Table;