import { useEffect, useRef, useState } from "react";

const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

export default function DatePicker() {
    const [selectedDate, setSelectedDate] = useState(null);
    const [isOpen, setIsOpen] = useState(false);
    const [currentMonth, setCurrentMonth] = useState(new Date().getMonth());
    const [currentYear, setCurrentYear] = useState(new Date().getFullYear());
    const ref = useRef();

    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const weekdays = ["S", "M", "T", "W", "T", "F", "S"];

    // Close picker when clicking outside
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (ref.current && !ref.current.contains(e.target)) {
                setIsOpen(false);
            }
        };
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const handleDateClick = (day) => {
        const date = new Date(currentYear, currentMonth, day);
        setSelectedDate(date);
        setIsOpen(false);
    };

    const nextMonth = () => {
        if (currentMonth === 11) {
            setCurrentMonth(0);
            setCurrentYear((y) => y + 1);
        } else setCurrentMonth((m) => m + 1);
    };

    const prevMonth = () => {
        if (currentMonth === 0) {
            setCurrentMonth(11);
            setCurrentYear((y) => y - 1);
        } else setCurrentMonth((m) => m - 1);
    };

    const formattedDate = selectedDate
        ? selectedDate.toISOString().split("T")[0]
        : "";

    const placeholder = selectedDate
        ? selectedDate.toLocaleDateString("en-US", { month: "long", day: "numeric", year: "numeric" })
        : "Select a date";

    return (
        <div className="relative inline-block" ref={ref}>
            <input
                type="text"
                className="w-56 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer"
                placeholder={placeholder}
                value={formattedDate}
                onFocus={() => setIsOpen(true)}
                readOnly
            />

            {isOpen && (
                <div className="absolute z-50 mt-2 bg-base-200 border border-gray-200 rounded-lg shadow-lg p-4 w-64">
                    {/* Header */}
                    <div className="flex justify-between items-center mb-3">
                        <button
                            onClick={prevMonth}
                            className="p-1 rounded hover:bg-gray-100 text-gray-600"
                        >
                            &lt;
                        </button>
                        <span className="text-gray-800 font-medium">
                            {months[currentMonth]} {currentYear}
                        </span>
                        <button
                            onClick={nextMonth}
                            className="p-1 rounded hover:bg-gray-100 text-gray-600"
                        >
                            &gt;
                        </button>
                    </div>

                    {/* Weekday headers */}
                    <div className="grid grid-cols-7 text-center text-xs font-semibold text-gray-500 mb-1">
                        {weekdays.map((d) => (
                            <div key={d}>{d}</div>
                        ))}
                    </div>

                    {/* Days grid */}
                    <div className="grid grid-cols-7 text-center text-sm">
                        {Array(firstDay).fill(null).map((_, i) => (
                            <div key={`empty-${i}`} />
                        ))}

                        {[...Array(daysInMonth)].map((_, i) => {
                            const day = i + 1;
                            const isSelected =
                                selectedDate &&
                                day === selectedDate.getDate() &&
                                currentMonth === selectedDate.getMonth() &&
                                currentYear === selectedDate.getFullYear();

                            return (
                                <div
                                    key={day}
                                    className={`p-2 rounded-md cursor-pointer transition-all duration-150 ${isSelected
                                            ? "bg-blue-600 text-primary-content"
                                            : "text-gray-700 hover:bg-blue-100"
                                        }`}
                                    onClick={() => handleDateClick(day)}
                                >
                                    {day}
                                </div>
                            );
                        })}
                    </div>
                </div>
            )}
        </div>
    );
}
