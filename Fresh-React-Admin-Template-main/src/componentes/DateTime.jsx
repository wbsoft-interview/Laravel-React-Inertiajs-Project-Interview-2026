
const DateTime = ({ dt }) => {
    if (!dt) return "N/A";

    const date = new Date(dt);

    // Create a localized date/time formatter
    const formatter = new Intl.DateTimeFormat("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });

    const formattedTime = formatter.format(date);

    const today = new Date();
    const isToday =
        date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear();

    const formattedDate = `${String(date.getDate()).padStart(2, "0")}/${date.toLocaleString("en-US", {
        month: "short",
    })}/${date.getFullYear()}`;

    // If it's today, only show the time — otherwise show full date + time
    return isToday ? formattedTime : `${formattedDate} ${formattedTime}`;
};

export default DateTime;
