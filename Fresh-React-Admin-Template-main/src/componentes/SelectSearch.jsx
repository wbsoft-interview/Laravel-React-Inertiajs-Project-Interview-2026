import Select from "react-select";

const SelectSearch = ({
  options = [
    { value: "1", label: "1" },
    { value: "01", label: "11" },
  ],
  selectOption,
  setSelectOption,
  placeholder = "Select...",
  isClearable = true,
  isDisabled = false,
}) => {
  const safeOptions = Array.isArray(options) ? options : [];

  const isDarkMode = localStorage.getItem("theme") ;

  const colors = isDarkMode
    ? {
      bg: "#1f2937",
      menuBg: "#111827",
      border: "#374151",
      text: "#e5e7eb",
      placeholder: "#9ca3af",
      hover: "#374151",
      selected: "#1DAA61",
    }
    : {
      bg: "#ffffff",
      menuBg: "#ffffff",
      border: "#d1d5db",
      text: "#111827",
      placeholder: "#6b7280",
      hover: "#e5e7eb",
      selected: "#1DAA61",
    };

  const selectStyles = {
    control: (base, state) => ({
      ...base,
      height: "40px",
      minHeight: "40px",
      backgroundColor: colors.bg,
      borderColor: state.isFocused ? "#1DAA61" : colors.border,
      boxShadow: state.isFocused ? "0 0 0 1px #1DAA61" : "none",
      "&:hover": {
        borderColor: "#1DAA61",
      },
    }),

    singleValue: (base) => ({
      ...base,
      color: colors.text,
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

    menuList: (base) => ({
      ...base,
      maxHeight: "200px",
      overflowY: "auto",
    }),
  };

  const selectedOption =
    safeOptions.find((opt) => String(opt?.value) === String(selectOption)) || null;

  return (
    <Select
      options={safeOptions}
      value={selectedOption}
      onChange={(opt) => setSelectOption(opt ? opt.value : null)}
      placeholder={placeholder}
      isClearable={isClearable}
      isDisabled={isDisabled}
      className="w-full"
      styles={selectStyles}
    />
  );
};

export default SelectSearch;
