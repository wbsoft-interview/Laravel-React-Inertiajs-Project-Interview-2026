import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { FaCheck } from "react-icons/fa";
import { FiEye, FiFilter } from "react-icons/fi";
import { GoPlusCircle } from "react-icons/go";
import { IoIosArrowDown, IoMdClose } from "react-icons/io";
import { MdBlock } from "react-icons/md";
import { RiResetLeftFill } from "react-icons/ri";

import { useQuery } from "@tanstack/react-query";
import { toast } from "react-toastify";

import UseAuth from "../Hooks/UseAuth";
import UseAxiosSecure from "../Hooks/UseAxiosSecure";
import Loader from "../componentes/Loader";
import SelectSearch from "../componentes/SelectSearch";

import { url } from "../../connection";
import BookDefaultPhoto from "../assets/logo/BookDefaultPhoto.jpg";
import { Translations } from "../utils/Translations";

// ---------------- helpers ----------------
const cx = (...c) => c.filter(Boolean).join(" ");

const PAGE_SIZE_OPTIONS = ["24", "48", "72", "100"];
const PAGE_SIZE_OPTIONS_FILTER = ["12", "24", "36", "48", "60", "72", "84", "100"];

// ---------------- component ----------------
const Cardtable = () => {
  const axiosSecure = UseAxiosSecure();
  const { loading, setLoading, language } = UseAuth();
  const t = Translations[language];
  const STATUS_OPTIONS = [
    { icon: <FiEye size={18} />, text: t.all, value:"All" },
    { icon: <FaCheck size={18} />, text: t.active, value:"Active" },
    { icon: <MdBlock size={18} />, text: t.inactive, value:"Inactive" },
  ];

  // panels
  const [openFilter, setOpenFilter] = useState(false);
  const [openImport, setOpenImport] = useState(false);
  const [panelHide, setPanelHide] = useState(true);

  // table state
  const [rows, setRows] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);

  // header controls
  const [openPageSize, setOpenPageSize] = useState(false);
  const [pageSizeValue, setPageSizeValue] = useState("24");
  const pageSize = useMemo(() => parseInt(pageSizeValue, 10) || 24, [pageSizeValue]);

  const [searchTerm, setSearchTerm] = useState("");
  const [searchEnabled, setSearchEnabled] = useState(true);

  const [openStatus, setOpenStatus] = useState(false);
  const [statusValue, setStatusValue] = useState("All");

  // filters
  const [classFilter, setClassFilter] = useState(null);
  const [search, setSearch] = useState(true);

  // import
  const [classValue, setClassValue] = useState(null);
  const [selectedSubjectIds, setSelectedSubjectIds] = useState([]);

  // refs
  const dropdownRef = useRef(null);
  const statusRef = useRef(null);

  // ---------- panel animations ----------
  const closePanel = useCallback(
    (setter) => {
      setPanelHide(false);
      setTimeout(() => {
        setter(false);
        setPanelHide(true);
        setClassValue(null);
        setSelectedSubjectIds([]);
      }, 300);
    },
    []
  );

  const closeFilterPanel = useCallback(() => closePanel(setOpenFilter), [closePanel]);
  const closeImportPanel = useCallback(() => closePanel(setOpenImport), [closePanel]);

  const openImportPanel = () => {
    setSelectedSubjectIds([]);
    setClassValue(null);
    setOpenImport(true);
  };

  // ---------- outside click / escape ----------
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) setOpenPageSize(false);
      if (statusRef.current && !statusRef.current.contains(event.target)) setOpenStatus(false);
    };
    const handleEscape = (e) => {
      if (e.key === "Escape") {
        setOpenPageSize(false);
        setOpenStatus(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    document.addEventListener("keydown", handleEscape);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
      document.removeEventListener("keydown", handleEscape);
    };
  }, []);

  // ---------- queries ----------
  const data = {
    "message": "Subject fetched successfully.",
    "status_code": 200,
    "subjectData": [
      {
        "id": 576,
        "user_id": 421,
        "global_subject_id": 82,
        "classname_id": 27,
        "subject_name": "Bangla 1st Paper",
        "photo": BookDefaultPhoto,
        "status": 1,
        "created_at": "27-12-2025 16:09:28",
        "updated_at": "27-12-2025 16:09:28",
        "class_data": {
          "id": 27,
          "user_id": 421,
          "global_class_id": 10,
          "class_name": "TEN (Science)",
          "status": 1,
          "created_at": "24-12-2025 16:47:52",
          "updated_at": "27-12-2025 13:52:29"
        }
      },
      {
        "id": 575,
        "user_id": 421,
        "global_subject_id": 83,
        "classname_id": 27,
        "subject_name": "Bangla 2nd Paper",
        "photo": BookDefaultPhoto,
        "status": 1,
        "created_at": "27-12-2025 16:09:28",
        "updated_at": "27-12-2025 16:09:28",
        "class_data": {
          "id": 27,
          "user_id": 421,
          "global_class_id": 10,
          "class_name": "TEN (Science)",
          "status": 1,
          "created_at": "24-12-2025 16:47:52",
          "updated_at": "27-12-2025 13:52:29"
        }
      },
      {
        "id": 574,
        "user_id": 421,
        "global_subject_id": 84,
        "classname_id": 27,
        "subject_name": "English 1st Paper",
        "photo": BookDefaultPhoto,
        "status": 1,
        "created_at": "27-12-2025 16:09:28",
        "updated_at": "27-12-2025 16:09:28",
        "class_data": {
          "id": 27,
          "user_id": 421,
          "global_class_id": 10,
          "class_name": "TEN (Science)",
          "status": 1,
          "created_at": "24-12-2025 16:47:52",
          "updated_at": "27-12-2025 13:52:29"
        }
      },],
    "allSubjectCount": 113,
    "classData": [
      {
        "id": 29,
        "user_id": 421,
        "global_class_id": 8,
        "class_name": "EIGHT",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 32,
        "user_id": 421,
        "global_class_id": 5,
        "class_name": "FIVE",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 33,
        "user_id": 421,
        "global_class_id": 4,
        "class_name": "FOUR",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 72,
        "user_id": 421,
        "global_class_id": 11,
        "class_name": "NINE (Humanities)",
        "status": 1,
        "created_at": "27-12-2025 13:54:29",
        "updated_at": "27-12-2025 13:54:29"
      },
      {
        "id": 28,
        "user_id": 421,
        "global_class_id": 9,
        "class_name": "NINE (Science)",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "27-12-2025 13:50:15"
      },
      {
        "id": 36,
        "user_id": 421,
        "global_class_id": 1,
        "class_name": "ONE",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 30,
        "user_id": 421,
        "global_class_id": 7,
        "class_name": "SEVEN",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 31,
        "user_id": 421,
        "global_class_id": 6,
        "class_name": "SIX",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 71,
        "user_id": 421,
        "global_class_id": 12,
        "class_name": "TEN (Humanities)",
        "status": 1,
        "created_at": "27-12-2025 13:54:29",
        "updated_at": "27-12-2025 13:54:29"
      },
      {
        "id": 27,
        "user_id": 421,
        "global_class_id": 10,
        "class_name": "TEN (Science)",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "27-12-2025 13:52:29"
      },
      {
        "id": 34,
        "user_id": 421,
        "global_class_id": 3,
        "class_name": "THREE",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      },
      {
        "id": 35,
        "user_id": 421,
        "global_class_id": 2,
        "class_name": "TWO",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "24-12-2025 16:47:52"
      }
    ]
  }

  const { data: subjectOptions = [], isLoading: subjectOptionsLoading } = useQuery({
    queryKey: ["importSubjectOptions", classValue],
    enabled: !!classValue,
    queryFn: async () => {
      try {
        const res = await axiosSecure.get(`/api/class-wise-global-subject/${classValue}`);
        if (res?.data?.status_code === 200) return res?.data?.globalSubjectData || [];
        return [];
      } catch (error) {
        const message =
          error?.response?.data?.message || error?.message || "Failed to fetch import subject data";
        toast.error(message);
        return [];
      }
    },
    retry: false,
  });

  // ---------- normalize rows ----------
  useEffect(() => {
    if (!data) return;

    const classList = data?.classData || [];
    const subjectList = data?.subjectData || [];

    const classMap = {};
    classList.forEach((c) => {
      classMap[c.id] = c.class_name;
    });

    const normalized = subjectList.map((s) => ({
      ...s,
      class_name: classMap[s.classname_id] || "",
    }));

    setRows(normalized);
  }, []);

  const classes = useMemo(
    () =>
      (data?.classData || []).map((c) => ({
        value: c.global_class_id,
        label: c.class_name,
      })),
    [data]
  );


  const importedByClass = useMemo(() => {
    const map = new Map();
    (rows || []).forEach((row) => {
      const classGlobalId = row?.class_data?.global_class_id;
      const subjectGlobalId = row?.global_subject_id;

      if (!classGlobalId || !subjectGlobalId) return;

      const key = String(classGlobalId);
      const val = String(subjectGlobalId);

      if (!map.has(key)) map.set(key, new Set());
      map.get(key).add(val);
    });

    return map;
  }, [rows]);

  useEffect(() => {
    if (!classValue) return;

    const importedSet = importedByClass.get(String(classValue)) || new Set();

    setSelectedSubjectIds((prev) => {
      const merged = new Set(prev.map((x) => Number(x)));
      importedSet.forEach((id) => merged.add(Number(id)));
      return Array.from(merged);
    });
  }, [classValue, subjectOptions, importedByClass]);

  // ---------- filter + search ----------
  const filteredRows = useMemo(() => {
    let out = rows || [];

    if (classFilter) {
      out = out.filter((r) => String(r?.classname_id) === String(classFilter));
    }

    if (statusValue === "Active") out = out.filter((r) => Number(r?.status) === 1);
    if (statusValue === "Inactive") out = out.filter((r) => Number(r?.status) === 0);

    const raw = (searchTerm || "").trim().toLowerCase();
    if (!raw) return out;

    const tokens = raw.split(/\s+/).filter(Boolean);
    if (!tokens.length) return out;

    return out.filter((row) => {
      const subject = String(row?.subject_name ?? "").toLowerCase();
      const classname = String(row?.class_name ?? "").toLowerCase();
      const combined = `${subject} ${classname}`;
      return tokens.every((t) => combined.includes(t));
    });
  }, [rows, classFilter, statusValue, searchTerm]);

  // ---------- pagination ----------
  const totalPages = useMemo(
    () => Math.max(1, Math.ceil((filteredRows?.length || 0) / pageSize)),
    [filteredRows, pageSize]
  );

  const currentPageSafe = Math.min(currentPage, totalPages);

  const startIndex = useMemo(() => (currentPageSafe - 1) * pageSize, [currentPageSafe, pageSize]);

  const currentRows = useMemo(
    () => filteredRows.slice(startIndex, startIndex + pageSize),
    [filteredRows, startIndex, pageSize]
  );

  const handlePageChange = (page) => {
    if (page >= 1 && page <= totalPages) setCurrentPage(page);
  };

  // ---------- handlers ----------
  const handleSelectPageSize = (v) => {
    setPageSizeValue(v);
    setOpenPageSize(false);
    setCurrentPage(1);
  };

  const handleStatus = (text) => {
    setStatusValue(text);
    setOpenStatus(false);
    setCurrentPage(1);
  };

  const handleFilterReset = () => {
    setClassFilter(null);
    setStatusValue("All");
    setPageSizeValue("24");
    setCurrentPage(1);
  };

  const handleFilterSubmit = (e) => {
    e.preventDefault();
    setCurrentPage(1);
    closeFilterPanel();
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    setSearch(!search);
    setSearchEnabled((prev) => {
      const next = !prev;
      const value = e.target.search?.value || "";
      if (prev) setSearchTerm(value);
      else {
        setSearchTerm("");
        e.target.reset();
      }
      return next;
    });
  };

  // ---------- import ----------
  const toggleSubjectId = (id) => {
    setSelectedSubjectIds((prev) => {
      const nid = Number(id);
      return prev.includes(nid) ? prev.filter((x) => x !== nid) : [...prev, nid];
    });
  };

  const handleImportSubmit = async (e) => {
    e.preventDefault();

    if (!classValue) {
      toast.error("Please select class.");
      return;
    }

    if (!selectedSubjectIds.length) {
      toast.error("Please select at least one subject.");
      return;
    }

    setLoading(true);
    try {
      const payload = { class_id: classValue, subject_id: selectedSubjectIds };
      const res = await axiosSecure.post("/api/save-subject-import-data", payload);

      if (res?.data?.status_code === 200) {
        toast.success(res?.data?.message || "Imported successfully");
        setSelectedSubjectIds([]);
        setClassValue(null);
        await refetchSubjectList();
        closeImportPanel();
      } else {
        toast.error(res?.data?.message || "Failed to import");
      }
    } catch (error) {
      const message =
        error?.response?.data?.errors?.[0] ||
        error?.response?.data?.message ||
        error?.message ||
        "Failed to import";
      toast.error(message);
    } finally {
      setLoading(false);
    }
  };

  if (subjectOptionsLoading) return <Loader />;

  return (
    <div className="relative w-full">
      <div className="p-5">
        <div className="w-full px-5 relative bg-base-200">
          {/* HEADER */}
          <div className="w-full py-3 text-primary-content flex flex-col lg:flex-row items-center justify-between gap-2 lg:gap-5">
            <h1 className="font-semibold text-3xl">{t.card}</h1>

            <div className="flex flex-col md:flex-row items-center justify-end gap-2">
              <div className="flex lg:flex-row-reverse items-center justify-end gap-2">
                {/* PAGE SIZE */}
                <div className="relative w-16" ref={dropdownRef}>
                  <button
                    onClick={() => setOpenPageSize(!openPageSize)}
                    className="w-full py-1.5 border-2 border-primary bg-base-200 rounded cursor-pointer flex items-center justify-center gap-2"
                    type="button"
                  >
                    {pageSizeValue}
                    <IoIosArrowDown
                      className={`transition-transform duration-300 ${openPageSize ? "-rotate-180" : ""}`}
                    />
                  </button>

                  <div
                    className={`absolute z-20 w-full mt-1 bg-base-200 border-2 border-primary rounded shadow overflow-hidden transition-all duration-200 origin-top ${openPageSize ? "opacity-100 scale-y-100" : "opacity-0 scale-y-0"
                      }`}
                  >
                    {PAGE_SIZE_OPTIONS.map((opt) => (
                      <div
                        key={opt}
                        onClick={() => handleSelectPageSize(opt)}
                        className={`${pageSizeValue === opt ? "bg-primary text-primary-content" : ""
                          } text-center py-2 hover:bg-primary-light hover:text-primary cursor-pointer`}
                      >
                        {opt}
                      </div>
                    ))}
                  </div>
                </div>

                {/* SEARCH */}
                <form onSubmit={handleSearchSubmit} className="flex items-center w-full">
                  <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder={t.search}
                    disabled={!search}
                    className={`border-2 border-primary w-full h-10 px-2 focus:outline-none rounded-s text-primary-content ${!search ? "bg-gray-200 cursor-not-allowed opacity-70" : ""
                      }`}
                  />
                  <button
                    type="submit"
                    className="flex items-center gap-1 font-bold text-primary-content bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded-e cursor-pointer"
                  >
                    {search ? t.search : t.reset}
                  </button>
                </form>
              </div>

              <div className="flex items-center w-full md:w-auto gap-2">
                {/* STATUS DROPDOWN */}
                <div ref={statusRef} className="relative w-full md:w-28">
                  <button
                    onClick={() => setOpenStatus(!openStatus)}
                    className="w-full py-1.5 px-2 border-2 border-primary bg-base-200 rounded cursor-pointer flex items-center justify-between gap-2"
                    type="button"
                  >
                    {STATUS_OPTIONS.find((opt) => opt.value === statusValue)?.text}
                    <IoIosArrowDown
                      className={`transition-transform duration-300 ${openStatus ? "-rotate-180" : ""}`}
                    />
                  </button>

                  <div
                    className={`absolute z-20 w-full mt-1 bg-base-200 border-2 border-primary rounded shadow overflow-hidden transition-all duration-200 origin-top ${openStatus ? "opacity-100 scale-y-100" : "opacity-0 scale-y-0"
                      }`}
                  >
                    {STATUS_OPTIONS.map((opt) => (
                      <div
                        key={opt.text}
                        onClick={() => handleStatus(opt.value)}
                        className={`${statusValue === opt.value ? "bg-primary text-primary-content" : ""
                          } text-center py-1 px-2 flex items-center gap-3 hover:text-primary hover:bg-primary-light cursor-pointer`}
                      >
                        {opt.icon} {opt.text}
                      </div>
                    ))}
                  </div>
                </div>

                {/* FILTER */}
                <button
                  onClick={() => setOpenFilter(true)}
                  className="flex items-center gap-1 font-bold text-primary bg-primary-light hover:bg-primary-light-hover px-3 py-2 text-base rounded cursor-pointer"
                >
                  <FiFilter />
                  {t.filter}
                </button>

                {/* ADD */}
                <button
                  // onClick={() => setOpenAdd(true)}
                  className="flex items-center gap-1 font-bold text-primary-content bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded cursor-pointer whitespace-nowrap"
                >
                  <GoPlusCircle />
                  {t.add}
                </button>
              </div>
            </div>
          </div>

          {/* GRID */}
          <div className="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-5 mt-4">
            {
              currentRows.map((row) => (
                <div
                  key={row?.id}
                  className="w-full border border-accent bg-base-200 shadow-md rounded-md hover:shadow-lg transition-shadow duration-300"
                >
                  <div className="relative">
                    <img
                      src={row?.photo}
                      alt={row?.subject_name}
                      className="w-full h-52 object-cover rounded-t-md"
                    />

                    <span
                      className={cx(
                        "absolute top-2 right-2 px-2 py-0.5 text-xs font-semibold rounded-full shadow",
                        Number(row?.status) === 1 ? "bg-green-600 text-primary-content" : "bg-red-600 text-primary-content"
                      )}
                    >
                      {Number(row?.status) === 1 ? t.active : t.inactive}
                    </span>
                  </div>

                  <div className="p-2 flex flex-col gap-1">
                    <h1 className="font-semibold text-lg text-primary truncate">{row?.subject_name}</h1>
                    <p className="text-base text-primary-content truncate">
                      {row?.class_name || "No class"}
                    </p>
                  </div>
                </div>
              )
              )}
          </div>

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

      {/* FILTER PANEL */}
      <div className={openFilter ? "fixed top-0 right-0 w-full h-screen bg-black/20 overflow-y-auto z-40" : "hidden"}>
        <div
          className={cx(
            "fixed top-0 right-0 w-full lg:w-4/12 bg-base-200 h-screen overflow-y-auto z-40",
            panelHide ? "slide-in" : "slide-out"
          )}
        >
          <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
            <h1 className="text-base sm:text-lg font-semibold">Filter</h1>
            <div className="flex items-center gap-3">
              <button type="button" onClick={handleFilterReset} className="text-primary-content hover:text-error">
                <RiResetLeftFill size={24} />
              </button>
              <button type="button" onClick={closeFilterPanel} className="text-primary-content hover:text-error">
                <IoMdClose size={24} />
              </button>
            </div>
          </div>

          {/* search (same behavior) */}
          <form onSubmit={handleSearchSubmit} className="flex items-center w-full my-3 px-3">
            <input
              type="text"
              id="search"
              name="search"
              placeholder="Search Here"
              disabled={!searchEnabled}
              className={cx(
                "border-2 border-primary w-full h-10 px-2 rounded-s text-primary-content",
                !searchEnabled && "bg-gray-200 cursor-not-allowed opacity-70"
              )}
            />
            <button type="submit" className="bg-primary text-primary-content px-3 py-2 rounded-e font-bold">
              {searchEnabled ? "Search" : "Reset"}
            </button>
          </form>

          <h1 className="px-5 py-2 text-primary font-bold bg-primary-light">Table Data Show</h1>
          <div className="px-5 py-2 grid grid-cols-8 gap-2">
            {PAGE_SIZE_OPTIONS_FILTER.map((opt) => (
              <button
                key={opt}
                type="button"
                onClick={() => handleSelectPageSize(opt)}
                className={cx(
                  "px-4 py-2 rounded-md cursor-pointer",
                  pageSizeValue === opt
                    ? "bg-primary text-primary-content"
                    : "bg-primary-light text-primary hover:bg-primary-hover hover:text-primary-content"
                )}
              >
                {opt}
              </button>
            ))}
          </div>

          <form className="mt-1 space-y-1" onSubmit={handleFilterSubmit}>
            <h1 className="px-5 py-2 text-primary font-bold bg-primary-light">Action</h1>

            <div className="px-5 py-2 grid grid-cols-3 gap-2">
              <button
                type="button"
                onClick={() => handleStatus("All")}
                className={cx(
                  "px-4 py-2 rounded-md",
                  statusValue === "All" ? "bg-blue-600 text-primary-content" : "bg-blue-500 text-primary-content hover:bg-blue-600"
                )}
              >
                All
              </button>
              <button
                type="button"
                onClick={() => handleStatus("Active")}
                className={cx(
                  "px-4 py-2 rounded-md",
                  statusValue === "Active"
                    ? "bg-primary text-primary-content"
                    : "bg-primary-light text-primary hover:bg-primary-hover hover:text-primary-content"
                )}
              >
                Active
              </button>
              <button
                type="button"
                onClick={() => handleStatus("Inactive")}
                className={cx(
                  "px-4 py-2 rounded-md",
                  statusValue === "Inactive"
                    ? "bg-red-500 text-primary-content"
                    : "bg-red-200 text-red-600 hover:bg-red-500 hover:text-primary-content"
                )}
              >
                Inactive
              </button>
            </div>

            <div className="flex items-center justify-end gap-3 px-2 pb-4">
              <button type="button" onClick={closeFilterPanel} className="bg-secondary text-primary-content py-2 px-4 rounded-md">
                Cancel
              </button>
              <button type="submit" className="bg-primary text-primary-content py-2 px-4 rounded-md">
                Apply
              </button>
            </div>
          </form>
        </div>
      </div>

      {/* IMPORT PANEL */}
      <div className={openImport ? "fixed top-0 right-0 w-full h-screen bg-black/20 overflow-y-auto z-40" : "hidden"}>
        <div
          className={cx(
            "fixed top-0 right-0 w-full lg:w-4/12 bg-base-200 h-screen overflow-y-auto z-40",
            panelHide ? "slide-in" : "slide-out"
          )}
        >
          <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
            <h1 className="text-base sm:text-lg font-semibold">Import Subject</h1>

            <button
              type="button"
              onClick={closeImportPanel}
              disabled={loading}
              className="text-sm text-primary-content cursor-pointer hover:bg-error hover:text-primary-content"
            >
              <IoMdClose size={24} />
            </button>
          </div>

          <form className="mt-3 space-y-3" onSubmit={handleImportSubmit}>
            <div className="px-3">
              <h1 className="font-semibold text-lg text-primary-content">
                Select Class <span className="text-error">*</span>
              </h1>
              <SelectSearch
                options={classes}
                selectOption={classValue}
                setSelectOption={setClassValue}
                placeholder="Select class"
              />
            </div>

            <div className="px-3 space-y-1">
              {subjectOptionsLoading ? (
                <div className="p-3">
                  <Loader />
                </div>
              ) : (
                <div className="grid grid-cols-1 gap-2">
                  {subjectOptions?.map((s) => {
                    const id = Number(s?.id);
                    const classGlobalId = String(s?.classname_id);

                    // imported?
                    const autoImported = importedByClass.get(classGlobalId)?.has(String(id)) || false;
                    const isChecked = selectedSubjectIds.includes(id);

                    return (
                      <button
                        key={id}
                        type="button"
                        onClick={() => toggleSubjectId(id)}
                        className="w-full flex items-center gap-2 px-3 py-2 rounded-md hover:bg-accent/60"
                      >
                        <input type="checkbox" className="checkbox" checked={isChecked} readOnly />
                        <span className="capitalize">{s?.subject_name}</span>

                        {autoImported && isChecked && <span className="ml-auto text-xs text-success">Imported</span>}
                        {autoImported && !isChecked && <span className="ml-auto text-xs text-warning">Removed</span>}
                      </button>
                    );
                  })}
                </div>
              )}
            </div>

            <div className="flex items-center justify-end gap-3 px-3 pb-6">
              <button
                type="button"
                onClick={closeImportPanel}
                className="bg-secondary text-primary-content py-2 px-4 rounded-md"
                disabled={loading}
              >
                Cancel
              </button>

              <button
                type="submit"
                className="bg-primary text-primary-content py-2 px-4 rounded-md disabled:opacity-60"
                disabled={loading}
              >
                {loading ? "Importing..." : "Import"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Cardtable;
