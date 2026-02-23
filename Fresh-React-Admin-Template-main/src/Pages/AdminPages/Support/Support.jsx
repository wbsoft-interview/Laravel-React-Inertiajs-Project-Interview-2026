import { useEffect, useMemo, useRef, useState } from "react";
import { toast } from "react-toastify";

import defaultPhoto from "../../../assets/logo/defaultPhoto.png";
import bgPhoto from "../../../assets/logo/bg.png";

import { FaArrowLeft, FaChevronDown, FaHeart, FaSearch } from "react-icons/fa";
import { IoMdClose } from "react-icons/io";
import { MdAddCircleOutline } from "react-icons/md";

import DateTime from "../../../componentes/DateTime";
import UseAuth from "../../../Hooks/UseAuth";
import { Translations } from "../../../utils/Translations";

// ---------------- MOCK DATA ----------------
const mockTickets = [
    {
        id: 1,
        ticket_number: "TK-1001",
        support_type: "High",
        subject: "Login problem",
        details: "I can't login to my account",
        status: 0,
        created_at: new Date(),
        updated_at: new Date(),
        ticket_by_data: {
            name: "John Doe",
            image: null,
        },
        conversation: [
            {
                id: 1,
                ticket_by_data: { name: "John Doe" },
                details: "Please help me",
                created_at: new Date(),
            },
        ],
    },
];

const Support = () => {
    const { language } = UseAuth();
  const t = Translations[language];
    // ---------------- STATE ----------------
    const [tickets, setTickets] = useState(mockTickets);
    const [activeTicketId, setActiveTicketId] = useState(null);

    const [search, setSearch] = useState("");
    const [activeFilter, setActiveFilter] = useState(0);

    const [openAdd, setOpenAdd] = useState(false);
    const [panelHide, setPanelHide] = useState(true);

    const [supportTypeValue, setSupportTypeValue] = useState("High");
    const [subjectValue, setSubjectValue] = useState("");
    const [detailsValue, setDetailsValue] = useState("");

    const filterOptions = [
        t.all,
        t.open,
        t.closed,
        t.high,
        t.medium,
        t.low,
    ];
    // ---------------- FILTER ----------------
    const filteredTickets = useMemo(() => {
        let list = [...tickets];

        if (search.trim()) {
            const q = search.toLowerCase();
            list = list.filter(
                (t) =>
                    t.ticket_number.toLowerCase().includes(q) ||
                    t.subject.toLowerCase().includes(q) ||
                    t.support_type.toLowerCase().includes(q)
            );
        }

        switch (activeFilter) {
            case 1:
                return list.filter((t) => t.status === 0);
            case 2:
                return list.filter((t) => t.status === 1);
            case 3:
                return list.filter((t) => t.support_type === "High");
            case 4:
                return list.filter((t) => t.support_type === "Medium");
            case 5:
                return list.filter((t) => t.support_type === "Low");
            default:
                return list;
        }
    }, [tickets, search, activeFilter]);

    const activeTicket = tickets.find((t) => t.id === activeTicketId);

    // ---------------- CREATE ----------------
    const handleCreateSubmit = (e) => {
        e.preventDefault();

        if (!subjectValue || !detailsValue) {
            toast.error("All fields are required");
            return;
        }

        const newTicket = {
            id: Date.now(),
            ticket_number: `TK-${Date.now().toString().slice(-4)}`,
            support_type: supportTypeValue,
            subject: subjectValue,
            details: detailsValue,
            status: 0,
            created_at: new Date(),
            updated_at: new Date(),
            ticket_by_data: { name: "You", image: null },
            conversation: [],
        };

        setTickets((prev) => [newTicket, ...prev]);
        toast.success("Ticket created");

        setSubjectValue("");
        setDetailsValue("");
        setSupportTypeValue("High");
        setPanelHide(true);
        setTimeout(() => setOpenAdd(false), 200);
    };

    // ---------------- UI ----------------
    return (
        <div className="sm:flex w-full h-screen">
            {/* SIDEBAR */}
            <div className={`w-full sm:w-[35%] bg-base-200 ${activeTicketId ? "hidden sm:block" : ""}`}>
                {/* Header */}
                <div className="flex justify-between items-center px-4 py-3">
                    <h1 className="text-lg font-bold">{t.support}</h1>
                    <MdAddCircleOutline size={24} className="cursor-pointer" onClick={() => { setOpenAdd(true); setPanelHide(false); }} />
                </div>

                {/* Search */}
                <div className="px-4">
                    <div className="flex items-center gap-2 bg-base-100 px-3 py-2 rounded-full">
                        <FaSearch />
                        <input
                            className="bg-transparent flex-1 outline-none"
                            placeholder={t.searchHere}
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>
                </div>

                {/* Filters */}
                <div className="flex gap-2 px-4 py-2 overflow-x-auto">
                    {filterOptions.map((f, i) => (
                        <button
                            key={i}
                            onClick={() => setActiveFilter(i)}
                            className={`px-3 py-1 rounded-full text-sm ${activeFilter === i ? "bg-primary text-white" : "bg-neutral"
                                }`}
                        >
                            {f}
                        </button>
                    ))}
                </div>

                {/* Ticket List */}
                <div className="px-3 space-y-1 overflow-y-auto h-[calc(100vh-160px)]">
                    {filteredTickets.map((t) => (
                        <div
                            key={t.id}
                            onClick={() => setActiveTicketId(t.id)}
                            className={`p-3 rounded-xl cursor-pointer ${activeTicketId === t.id ? "bg-neutral" : "hover:bg-neutral"
                                }`}
                        >
                            <div className="flex justify-between">
                                <h2 className="text-sm font-medium">{t.ticket_by_data.name}</h2>
                                <span className="text-xs text-primary-content">
                                    <DateTime dt={t.created_at} />
                                </span>
                            </div>

                            <p className="text-xs text-primary-content truncate">{t.subject}</p>
                        </div>
                    ))}
                </div>
            </div>

            {/* DETAILS */}
            <div
                className={`flex-1 bg-cover bg-center ${!activeTicketId ? "hidden sm:block" : ""}`}
                style={{ backgroundImage: `url(${bgPhoto})` }}
            >
                {!activeTicket ? (
                    <div className="h-full flex items-center justify-center text-primary-content">
                        Select a ticket
                    </div>
                ) : (
                    <div className="p-4 space-y-3">
                        <button className="sm:hidden" onClick={() => setActiveTicketId(null)}>
                            <FaArrowLeft />
                        </button>

                        <div className="bg-base-200 p-4 rounded-xl">
                            <h2 className="font-semibold">{activeTicket.subject}</h2>
                            <p className="text-sm mt-2">{activeTicket.details}</p>
                        </div>
                    </div>
                )}
            </div>

            {/* CREATE PANEL */}
            {openAdd && (
                <div className="fixed top-0 right-0 w-full lg:w-1/3 h-full bg-base-200 p-5 z-50">
                    <div className="flex justify-between items-center">
                        <h2 className="font-semibold">{t.create} {t.tickets}</h2>
                        <IoMdClose onClick={() => setOpenAdd(false)} className="cursor-pointer" />
                    </div>

                    <form onSubmit={handleCreateSubmit} className="space-y-3 mt-4">
                        <select
                            value={supportTypeValue}
                            onChange={(e) => setSupportTypeValue(e.target.value)}
                            className="w-full border p-2 rounded"
                        >
                            <option value="high">{t.high}</option>
                            <option value="medium">{t.medium}</option>
                            <option value="low">{t.low}</option>
                        </select>

                        <input
                            value={subjectValue}
                            onChange={(e) => setSubjectValue(e.target.value)}
                            placeholder={t.subject}
                            className="w-full border p-2 rounded"
                        />

                        <textarea
                            value={detailsValue}
                            onChange={(e) => setDetailsValue(e.target.value)}
                            placeholder={t.details}
                            rows={4}
                            className="w-full border p-2 rounded"
                        />

                        <button className="w-full bg-primary text-white py-2 rounded">
                            {t.create}
                        </button>
                    </form>
                </div>
            )}
        </div>
    );
};

export default Support;
