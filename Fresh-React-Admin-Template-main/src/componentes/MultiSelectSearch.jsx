import Select from "react-select";

const MultiSelectSearch = ({
    options = [
        { value: "1", label: "1" },
        { value: "01", label: "11" },
    ],
    values = [],
    setValues,
    placeholder = "Select...",
    isDisabled = false,
}) => {
    const safeOptions = Array.isArray(options) ? options : [];

    const isDarkMode = localStorage.getItem("theme");

    const colors = isDarkMode
        ? {
            bg: "#1f2937",
            menuBg: "#111827",
            border: "#374151",
            text: "#e5e7eb",
            placeholder: "#9ca3af",
            hover: "#374151",
            selected: "#1DAA61",
            chipBg: "#374151",
            chipText: "#e5e7eb",
        }
        : {
            bg: "#ffffff",
            menuBg: "#ffffff",
            border: "#d1d5db",
            text: "#111827",
            placeholder: "#6b7280",
            hover: "#e5e7eb",
            selected: "#1DAA61",
            chipBg: "#e5e7eb",
            chipText: "#111827",
        };

    const selectedOptions = safeOptions.filter((opt) =>
        values.map(String).includes(String(opt?.value))
    );

    const selectStyles = {
        control: (base, state) => ({
            ...base,
            minHeight: "40px",
            backgroundColor: colors.bg,
            borderColor: state.isFocused ? "#1DAA61" : colors.border,
            boxShadow: state.isFocused ? "0 0 0 1px #1DAA61" : "none",
            "&:hover": {
                borderColor: "#1DAA61",
            },
        }),

        input: (base) => ({
            ...base,
            color: colors.text,
        }),

        placeholder: (base) => ({
            ...base,
            color: colors.placeholder,
        }),

        menu: (base) => ({
            ...base,
            backgroundColor: colors.menuBg,
        }),

        option: (base, state) => ({
            ...base,
            backgroundColor: state.isSelected
                ? colors.selected
                : state.isFocused
                    ? colors.hover
                    : colors.menuBg,
            color: state.isSelected ? "#fff" : colors.text,
            cursor: "pointer",
        }),

        multiValue: (base) => ({
            ...base,
            backgroundColor: colors.chipBg,
            borderRadius: "6px",
        }),

        multiValueLabel: (base) => ({
            ...base,
            color: colors.chipText,
            fontSize: "13px",
        }),

        multiValueRemove: (base) => ({
            ...base,
            color: colors.chipText,
            cursor: "pointer",
            ":hover": {
                backgroundColor: "#ef4444",
                color: "#fff",
            },
        }),

        menuList: (base) => ({
            ...base,
            maxHeight: "200px",
            overflowY: "auto",
        }),
    };

    return (
        <Select
            options={safeOptions}
            value={selectedOptions}
            onChange={(opts) => setValues((opts || []).map((o) => o.value))}
            placeholder={placeholder}
            isMulti
            isDisabled={isDisabled}
            className="w-full"
            styles={selectStyles}
        />
    );
};

export default MultiSelectSearch;
