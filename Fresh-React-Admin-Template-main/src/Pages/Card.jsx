import { useMutation, useQuery } from "@tanstack/react-query";
import { useEffect, useMemo, useRef, useState } from "react";
import { BsThreeDotsVertical } from "react-icons/bs";
import { FaCheck } from "react-icons/fa";
import { FiEye, FiFilter } from "react-icons/fi";
import { GoPencil, GoPlusCircle } from "react-icons/go";
import { IoIosArrowDown, IoMdClose } from "react-icons/io";
import { MdBlock } from "react-icons/md";
import { RiDeleteBinLine, RiResetLeftFill } from "react-icons/ri";
import { toast } from "react-toastify";
import Swal from "sweetalert2";
import { url } from "../../connection";
import UseAuth from "../Hooks/UseAuth";
import UseAxiosSecure from "../Hooks/UseAxiosSecure";
import defaultPhoto from "../assets/logo/defaultPhoto.png";
import uploadimage from "../assets/logo/uploadimage.jpg";
import Loader from "../componentes/Loader";
import SelectSearch from "../componentes/SelectSearch";
import { Translations } from "../utils/Translations";


const PAGE_SIZE_OPTIONS = ["24", "48", "72", "100"];
const PAGE_SIZE_OPTIONS_FILTER = ["12", "24", "36", "48", "60", "72", "84", "100"];

// ====== PHOTO HELPERS ======
const STUDENT_PHOTO_PATH = "/storage/uploads/studentFile/";
const buildPhotoUrl = (photoNameOrUrl) => {
  if (!photoNameOrUrl) return null;

  if (
    typeof photoNameOrUrl === "string" &&
    (photoNameOrUrl.startsWith("http") || photoNameOrUrl.startsWith("blob:"))
  ) {
    return photoNameOrUrl;
  }

  return `${url}${STUDENT_PHOTO_PATH}${photoNameOrUrl}`;
};

const safeRevokeBlob = (u) => {
  if (u && typeof u === "string" && u.startsWith("blob:")) URL.revokeObjectURL(u);
};

const Card = () => {
  const { loading, setLoading, language } = UseAuth();
  const t = Translations[language];
  const ITEMS = [t.serial, t.name, t.class, t.session_year, t.subject, t.date_time, t.status, t.actions];
  const STATUS_OPTIONS = [
    { icon: <FiEye size={18} />, text: t.all, value:"All" },
    { icon: <FaCheck size={18} />, text: t.active, value:"Active" },
    { icon: <MdBlock size={18} />, text: t.inactive, value:"Inactive" },
  ];

  const [openUpdate, setOpenUpdate] = useState(false);
  const [panelHide, setPanelHide] = useState(true);
  const [openAdd, setOpenAdd] = useState(false);
  const [openFilter, setOpenFilter] = useState(false);

  const [popOpen, setPopOpen] = useState(null);

  const [rows, setRows] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);

  const [openStatus, setOpenStatus] = useState(false);
  const [statusValue, setStatusValue] = useState("All");

  const [openPageSize, setOpenPageSize] = useState(false);
  const [pageSizeValue, setPageSizeValue] = useState("24");
  const pageSize = parseInt(pageSizeValue, 10) || 10;

  const [searchTerm, setSearchTerm] = useState("");
  const [editingRow, setEditingRow] = useState(null);

  // Student info
  const [studentNameValue, setStudentNameValue] = useState(null);
  const [studentPhoneValue, setStudentPhoneValue] = useState(null);
  const [studentEmailValue, setStudentEmailValue] = useState(null);
  const [rollNoValue, setRollNoValue] = useState(null);
  const [genderValue, setGenderValue] = useState(null);

  // Father info
  const [fatherNameValue, setFatherNameValue] = useState(null);
  const [fatherPhoneValue, setFatherPhoneValue] = useState(null);
  const [fatherProfessionValue, setFatherProfessionValue] = useState(null);

  // Mother info
  const [motherNameValue, setMotherNameValue] = useState(null);
  const [motherPhoneValue, setMotherPhoneValue] = useState(null);
  const [motherProfessionValue, setMotherProfessionValue] = useState(null);

  // Other info
  const [addressValue, setAddressValue] = useState(null);
  const [dateOfBirthValue, setDateOfBirthValue] = useState(null);
  const [admissionDateValue, setAdmissionDateValue] = useState(null);

  // Academic info
  const [sessionYearValue, setSessionYearValue] = useState(null);
  const [classValue, setClassValue] = useState(null);
  const [sectionValue, setSectionValue] = useState(null);
  const [groupValue, setGroupValue] = useState(null);

  const [checkedItems, setCheckedItems] = useState(ITEMS.map(() => true));
  const [search, setSearch] = useState(true);

  // CREATE Image
  const [image, setImage] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null);

  // UPDATE Image
  const [updateImage, setUpdateImage] = useState(null);
  const [updatePreviewUrl, setUpdatePreviewUrl] = useState(null);

  const dropdownRef = useRef(null);
  const statusRef = useRef(null);
  const fileInputRef = useRef(null);
  const updateFileInputRef = useRef(null);

  // click outside / esc / scroll
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setOpenPageSize(false);
      }
      if (statusRef.current && !statusRef.current.contains(event.target)) {
        setOpenStatus(false);
      }

      const isMenuClick = event.target.closest?.("[data-student-menu]");
      if (!isMenuClick) setPopOpen(null);
    };

    const handleEscape = (e) => e.key === "Escape" && setPopOpen(null);
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

  const data = {
    "message": "Student fetched successfully.",
    "status_code": 200,
    "studentData": [
      {
        "id": 10845,
        "user_id": 421,
        "session_year_id": 3,
        "classname_id": 71,
        "section_id": 19,
        "group_id": 5,
        "student_id": 10845,
        "guardian_id": 10845,
        "roll_no": "49",
        "status": 1,
        "created_at": "27-12-2025 17:36:39",
        "updated_at": "27-12-2025 17:36:39",
        "class_data": {
          "id": 71,
          "user_id": 421,
          "global_class_id": 12,
          "class_name": "TEN (Humanities)",
          "status": 1,
          "created_at": "27-12-2025 13:54:29",
          "updated_at": "27-12-2025 13:54:29"
        },
        "section_data": {
          "id": 19,
          "user_id": 421,
          "classname_id": 71,
          "section_name": "HUMANITIES",
          "status": 1,
          "created_at": "26-12-2025 20:56:43",
          "updated_at": "27-12-2025 16:00:52"
        },
        "student_data": {
          "id": 10845,
          "user_id": 421,
          "login_id": "st-202510845",
          "student_name": "বিপ্লব টুডূ",
          "student_email": null,
          "student_phone": "01700000000",
          "gender": null,
          "blood_group": null,
          "religion": null,
          "address": null,
          "date_of_birth": null,
          "addmission_date": null,
          "registration_no": "TS20251210945",
          "roll_no": "49",
          "student_photo": null,
          "student_birth_certificate": null,
          "student_transfer_certificate": null,
          "guardian_nid_1": null,
          "guardian_nid_2": null,
          "session_year": null,
          "is_demo": 0,
          "is_shifted": 0,
          "created_at": "27-12-2025 17:36:38",
          "updated_at": "27-12-2025 17:36:38"
        },
        "group_data": {
          "id": 5,
          "user_id": 421,
          "group_name": "HUMANITIES",
          "status": 1,
          "created_at": "27-12-2025 11:15:35",
          "updated_at": "27-12-2025 11:15:35"
        },
        "session_year_data": {
          "id": 3,
          "user_id": 421,
          "session_year": "2025",
          "start_month": "January",
          "end_month": "December",
          "status": 1,
          "created_at": "24-12-2025 16:42:14",
          "updated_at": "24-12-2025 16:42:14"
        }
      },
      {
        "id": 10844,
        "user_id": 421,
        "session_year_id": 3,
        "classname_id": 71,
        "section_id": 19,
        "group_id": 5,
        "student_id": 10844,
        "guardian_id": 10844,
        "roll_no": "48",
        "status": 1,
        "created_at": "27-12-2025 17:36:38",
        "updated_at": "27-12-2025 17:36:38",
        "class_data": {
          "id": 71,
          "user_id": 421,
          "global_class_id": 12,
          "class_name": "TEN (Humanities)",
          "status": 1,
          "created_at": "27-12-2025 13:54:29",
          "updated_at": "27-12-2025 13:54:29"
        },
        "section_data": {
          "id": 19,
          "user_id": 421,
          "classname_id": 71,
          "section_name": "HUMANITIES",
          "status": 1,
          "created_at": "26-12-2025 20:56:43",
          "updated_at": "27-12-2025 16:00:52"
        },
        "student_data": {
          "id": 10844,
          "user_id": 421,
          "login_id": "st-202510844",
          "student_name": "গুরুদাস",
          "student_email": null,
          "student_phone": "01700000000",
          "gender": null,
          "blood_group": null,
          "religion": null,
          "address": null,
          "date_of_birth": null,
          "addmission_date": null,
          "registration_no": "TS20251210944",
          "roll_no": "48",
          "student_photo": null,
          "student_birth_certificate": null,
          "student_transfer_certificate": null,
          "guardian_nid_1": null,
          "guardian_nid_2": null,
          "session_year": null,
          "is_demo": 0,
          "is_shifted": 0,
          "created_at": "27-12-2025 17:36:38",
          "updated_at": "27-12-2025 17:36:38"
        },
        "group_data": {
          "id": 5,
          "user_id": 421,
          "group_name": "HUMANITIES",
          "status": 1,
          "created_at": "27-12-2025 11:15:35",
          "updated_at": "27-12-2025 11:15:35"
        },
        "session_year_data": {
          "id": 3,
          "user_id": 421,
          "session_year": "2025",
          "start_month": "January",
          "end_month": "December",
          "status": 1,
          "created_at": "24-12-2025 16:42:14",
          "updated_at": "24-12-2025 16:42:14"
        }
      },
      {
        "id": 10843,
        "user_id": 421,
        "session_year_id": 3,
        "classname_id": 71,
        "section_id": 19,
        "group_id": 5,
        "student_id": 10843,
        "guardian_id": 10843,
        "roll_no": "47",
        "status": 1,
        "created_at": "27-12-2025 17:36:38",
        "updated_at": "27-12-2025 17:36:38",
        "class_data": {
          "id": 71,
          "user_id": 421,
          "global_class_id": 12,
          "class_name": "TEN (Humanities)",
          "status": 1,
          "created_at": "27-12-2025 13:54:29",
          "updated_at": "27-12-2025 13:54:29"
        },
        "section_data": {
          "id": 19,
          "user_id": 421,
          "classname_id": 71,
          "section_name": "HUMANITIES",
          "status": 1,
          "created_at": "26-12-2025 20:56:43",
          "updated_at": "27-12-2025 16:00:52"
        },
        "student_data": {
          "id": 10843,
          "user_id": 421,
          "login_id": "st-202510843",
          "student_name": "জয়দেব রায়",
          "student_email": null,
          "student_phone": "01700000000",
          "gender": null,
          "blood_group": null,
          "religion": null,
          "address": null,
          "date_of_birth": null,
          "addmission_date": null,
          "registration_no": "TS20251210943",
          "roll_no": "47",
          "student_photo": null,
          "student_birth_certificate": null,
          "student_transfer_certificate": null,
          "guardian_nid_1": null,
          "guardian_nid_2": null,
          "session_year": null,
          "is_demo": 0,
          "is_shifted": 0,
          "created_at": "27-12-2025 17:36:37",
          "updated_at": "27-12-2025 17:36:37"
        },
        "group_data": {
          "id": 5,
          "user_id": 421,
          "group_name": "HUMANITIES",
          "status": 1,
          "created_at": "27-12-2025 11:15:35",
          "updated_at": "27-12-2025 11:15:35"
        },
        "session_year_data": {
          "id": 3,
          "user_id": 421,
          "session_year": "2025",
          "start_month": "January",
          "end_month": "December",
          "status": 1,
          "created_at": "24-12-2025 16:42:14",
          "updated_at": "24-12-2025 16:42:14"
        }
      },
      {
        "id": 10842,
        "user_id": 421,
        "session_year_id": 3,
        "classname_id": 71,
        "section_id": 19,
        "group_id": 5,
        "student_id": 10842,
        "guardian_id": 10842,
        "roll_no": "46",
        "status": 1,
        "created_at": "27-12-2025 17:36:37",
        "updated_at": "27-12-2025 17:36:37",
        "class_data": {
          "id": 71,
          "user_id": 421,
          "global_class_id": 12,
          "class_name": "TEN (Humanities)",
          "status": 1,
          "created_at": "27-12-2025 13:54:29",
          "updated_at": "27-12-2025 13:54:29"
        },
        "section_data": {
          "id": 19,
          "user_id": 421,
          "classname_id": 71,
          "section_name": "HUMANITIES",
          "status": 1,
          "created_at": "26-12-2025 20:56:43",
          "updated_at": "27-12-2025 16:00:52"
        },
        "student_data": {
          "id": 10842,
          "user_id": 421,
          "login_id": "st-202510842",
          "student_name": "রশিদুল ইসলাম",
          "student_email": null,
          "student_phone": "01700000000",
          "gender": null,
          "blood_group": null,
          "religion": null,
          "address": null,
          "date_of_birth": null,
          "addmission_date": null,
          "registration_no": "TS20251210942",
          "roll_no": "46",
          "student_photo": null,
          "student_birth_certificate": null,
          "student_transfer_certificate": null,
          "guardian_nid_1": null,
          "guardian_nid_2": null,
          "session_year": null,
          "is_demo": 0,
          "is_shifted": 0,
          "created_at": "27-12-2025 17:36:36",
          "updated_at": "27-12-2025 17:36:36"
        },
        "group_data": {
          "id": 5,
          "user_id": 421,
          "group_name": "HUMANITIES",
          "status": 1,
          "created_at": "27-12-2025 11:15:35",
          "updated_at": "27-12-2025 11:15:35"
        },
        "session_year_data": {
          "id": 3,
          "user_id": 421,
          "session_year": "2025",
          "start_month": "January",
          "end_month": "December",
          "status": 1,
          "created_at": "24-12-2025 16:42:14",
          "updated_at": "24-12-2025 16:42:14"
        }
      }
    ],
    "allStudentCount": 399,
    "activeStudentCount": 399,
    "inactiveStudentCount": 0,
    "getBloodGroup": [
      "A+",
      "A-",
      "B+",
      "B-",
      "O+",
      "O-",
      "AB+",
      "AB-"
    ],
    "classData": [
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
        "id": 28,
        "user_id": 421,
        "global_class_id": 9,
        "class_name": "NINE (Science)",
        "status": 1,
        "created_at": "24-12-2025 16:47:52",
        "updated_at": "27-12-2025 13:50:15"
      },
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
        "id": 71,
        "user_id": 421,
        "global_class_id": 12,
        "class_name": "TEN (Humanities)",
        "status": 1,
        "created_at": "27-12-2025 13:54:29",
        "updated_at": "27-12-2025 13:54:29"
      },
      {
        "id": 72,
        "user_id": 421,
        "global_class_id": 11,
        "class_name": "NINE (Humanities)",
        "status": 1,
        "created_at": "27-12-2025 13:54:29",
        "updated_at": "27-12-2025 13:54:29"
      }
    ],
    "sectionData": [
      null
    ],
    "groupData": [
      {
        "id": 3,
        "user_id": 421,
        "group_name": "Common",
        "status": 1,
        "created_at": "24-12-2025 17:47:51",
        "updated_at": "24-12-2025 17:47:51"
      },
      {
        "id": 4,
        "user_id": 421,
        "group_name": "SCIENCE",
        "status": 1,
        "created_at": "27-12-2025 11:15:19",
        "updated_at": "27-12-2025 11:15:19"
      },
      {
        "id": 5,
        "user_id": 421,
        "group_name": "HUMANITIES",
        "status": 1,
        "created_at": "27-12-2025 11:15:35",
        "updated_at": "27-12-2025 11:15:35"
      }
    ],
    "sessionYearData": [
      {
        "id": 3,
        "user_id": 421,
        "session_year": "2025",
        "start_month": "January",
        "end_month": "December",
        "status": 1,
        "created_at": "24-12-2025 16:42:14",
        "updated_at": "24-12-2025 16:42:14"
      }
    ],
    "defaultSessionYear": {
      "id": 3,
      "user_id": 421,
      "session_year": "2025",
      "start_month": "January",
      "end_month": "December",
      "status": 1,
      "created_at": "24-12-2025 16:42:14",
      "updated_at": "24-12-2025 16:42:14"
    }
  }

  useEffect(() => {
    if (Array.isArray(data?.studentData)) {
      setRows(data.studentData);
    }
  }, [1]);

  // auto set default session year
  useEffect(() => {
    if (data?.defaultSessionYear?.id) setSessionYearValue(data.defaultSessionYear.id);
  }, [data]);

  const classes = useMemo(
    () => (data?.classData ?? []).filter(Boolean).map((c) => ({ value: c.id, label: c.class_name })),
    [data]
  );

  const sectionData = [];

  const sections = sectionData?.map((c) => ({
    value: c?.id,
    label: c?.section_name,
  }));

  const groups = useMemo(
    () => (data?.groupData ?? []).filter(Boolean).map((g) => ({ value: g.id, label: g.group_name })),
    [data]
  );

  const sessionYears = useMemo(
    () =>
      (data?.sessionYearData ?? [])
        .filter(Boolean)
        .map((y) => ({
          value: y.id,
          label: `${y.session_year ?? ""} ${y.start_month ?? ""}`.trim(),
        })),
    [data]
  );

  // filter
  const filteredRows = useMemo(() => {
    const term = searchTerm.trim().toLowerCase();

    return (rows ?? []).filter((row) => {
      const name = String(row?.student_data?.student_name || "").toLowerCase();
      const className = String(row?.class_data?.class_name || "").toLowerCase();

      const matchesSearch = term ? name.includes(term) || className.includes(term) : true;

      const matchesStatus =
        statusValue === "All" ? true : statusValue === "Active" ? row?.status === 1 : row?.status === 0;

      return matchesSearch && matchesStatus;
    });
  }, [rows, searchTerm, statusValue]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    setSearch(!search);
    if (search) setSearchTerm(e.target.search.value);
    else {
      setSearchTerm("");
      e.target.reset();
    }
  };

  const handleStatus = (v) => {
    setStatusValue(v);
    setOpenStatus(false);
    setCurrentPage(1);
  };

  const handleSelectPageSize = (v) => {
    setPageSizeValue(v);
    setOpenPageSize(false);
    setCurrentPage(1);
  };

  const totalPages = Math.max(1, Math.ceil(filteredRows.length / pageSize));
  const currentPageSafe = Math.min(currentPage, totalPages);
  const startIndex = (currentPageSafe - 1) * pageSize;
  const currentRows = filteredRows.slice(startIndex, startIndex + pageSize);

  const handlePageChange = (page) => {
    if (page >= 1 && page <= totalPages) {
      setCurrentPage(page);
      setPopOpen(null);
    }
  };

  const toggleChecked = (index) => {
    setCheckedItems((prev) => prev.map((val, i) => (i === index ? !val : val)));
  };

  const handleFilterSubmit = (e) => {
    e.preventDefault();
    setOpenFilter(false);
  };

  const handleFilterReset = () => setCheckedItems(ITEMS.map(() => true));

  const reset = () => {
    setStudentNameValue(null);
    setStudentPhoneValue(null);
    setStudentEmailValue(null);
    setRollNoValue(null);
    setGenderValue(null);

    setFatherNameValue(null);
    setFatherPhoneValue(null);
    setFatherProfessionValue(null);

    setMotherNameValue(null);
    setMotherPhoneValue(null);
    setMotherProfessionValue(null);

    setAddressValue(null);
    setDateOfBirthValue(null);
    setAdmissionDateValue(null);

    setClassValue(null);
    setSectionValue(null);
    setGroupValue(null);

    // create image
    setImage(null);
    safeRevokeBlob(previewUrl);
    setPreviewUrl(null);
    if (fileInputRef.current) fileInputRef.current.value = "";

    // update image
    setUpdateImage(null);
    safeRevokeBlob(updatePreviewUrl);
    setUpdatePreviewUrl(null);
    if (updateFileInputRef.current) updateFileInputRef.current.value = "";
  };

  const closeAddPanel = () => {
    setPanelHide(false);
    setTimeout(() => {
      setOpenAdd(false);
      setOpenFilter(false);
      setOpenUpdate(false);
      setEditingRow(null);
      setPopOpen(null);
      setPanelHide(true);
    }, 300);
  };

  // ===== CREATE =====
  const handleCreateSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      const formData = new FormData();
      formData.append("session_year_id", String(sessionYearValue ?? ""));
      formData.append("classname_id", String(classValue ?? ""));
      formData.append("section_id", String(sectionValue ?? ""));
      formData.append("group_id", String(groupValue ?? ""));

      formData.append("student_name", (studentNameValue ?? "").trim());
      formData.append("student_phone", studentPhoneValue ?? "");
      formData.append("student_email", studentEmailValue ?? "");

      formData.append("father_name", fatherNameValue ?? "");
      formData.append("phone", fatherPhoneValue ?? "");
      formData.append("father_profession", fatherProfessionValue ?? "");

      formData.append("mother_name", motherNameValue ?? "");
      formData.append("mother_phone", motherPhoneValue ?? "");
      formData.append("mother_profession", motherProfessionValue ?? "");

      formData.append("address", addressValue ?? "");
      formData.append("date_of_birth", dateOfBirthValue ?? "");
      formData.append("admission_date", admissionDateValue ?? "");
      formData.append("addmission_date", admissionDateValue ?? "");

      formData.append("roll_no", rollNoValue ?? "");
      formData.append("gender", genderValue ?? "");

      // only if user selects image
      if (image) formData.append("student_photo", image);

      console.log(formData)
      setLoading(false);
    } catch (error) {
      console.log("POST /api/student error:", error?.response?.data || error);
      toast.error(
        error?.response?.data?.errors?.[0] ||
        error?.response?.data?.message ||
        error?.message ||
        "Failed to create student"
      );
    } finally {
      setLoading(false);
    }
  };

  const handleActionUpdate = (rowId) => {
    const row = rows.find((r) => r.id === rowId);
    if (!row) return;

    setEditingRow(row);

    setSessionYearValue(row?.session_year_id ?? null);
    setClassValue(row?.classname_id ?? null);
    setSectionValue(row?.section_id ?? null);
    setGroupValue(row?.group_id ?? null);

    setStudentNameValue(row?.student_data?.student_name ?? "");
    setStudentPhoneValue(row?.student_data?.student_phone ?? "");
    setStudentEmailValue(row?.student_data?.student_email ?? "");
    setRollNoValue(row?.student_data?.roll_no ?? row?.roll_no ?? "");
    setGenderValue(row?.student_data?.gender ?? "");

    setAddressValue(row?.student_data?.address ?? "");
    setDateOfBirthValue(row?.student_data?.date_of_birth ?? "");
    setAdmissionDateValue(row?.student_data?.admission_date ?? row?.student_data?.addmission_date ?? "");

    setFatherNameValue(row?.guardian_data?.father_name ?? "");
    setFatherPhoneValue(row?.guardian_data?.phone ?? "");
    setFatherProfessionValue(row?.guardian_data?.father_profession ?? "");

    setMotherNameValue(row?.guardian_data?.mother_name ?? "");
    setMotherPhoneValue(row?.guardian_data?.mother_phone ?? "");
    setMotherProfessionValue(row?.guardian_data?.mother_profession ?? "");

    // keep old photo preview as full url
    const existingPhoto = row?.student_data?.student_photo || row?.image || null;
    setUpdateImage(null);
    safeRevokeBlob(updatePreviewUrl);
    setUpdatePreviewUrl(buildPhotoUrl(existingPhoto));

    setOpenUpdate(true);
    setPopOpen(null);
  };

  const handleUpdateSubmit = async (e) => {
    e.preventDefault();
    if (!editingRow?.id) return toast.error("No student selected");

    setLoading(true);
    try {
      const formData = new FormData();

      formData.append("session_year_id", String(sessionYearValue ?? ""));
      formData.append("classname_id", String(classValue ?? ""));
      formData.append("section_id", String(sectionValue ?? ""));
      formData.append("group_id", String(groupValue ?? ""));

      formData.append("student_name", (studentNameValue ?? "").trim());
      formData.append("student_phone", studentPhoneValue ?? "");
      formData.append("student_email", studentEmailValue ?? "");

      formData.append("father_name", fatherNameValue ?? "");
      formData.append("phone", fatherPhoneValue ?? "");
      formData.append("father_profession", fatherProfessionValue ?? "");

      formData.append("mother_name", motherNameValue ?? "");
      formData.append("mother_phone", motherPhoneValue ?? "");
      formData.append("mother_profession", motherProfessionValue ?? "");

      formData.append("address", addressValue ?? "");
      formData.append("date_of_birth", dateOfBirthValue ?? "");
      formData.append("admission_date", admissionDateValue ?? "");
      formData.append("addmission_date", admissionDateValue ?? "");

      formData.append("roll_no", rollNoValue ?? "");
      formData.append("gender", genderValue ?? "");

      // only if user selects new image
      if (updateImage) formData.append("student_photo", updateImage);

      console.log(formData)
      setLoading(false);
    } catch (error) {
      console.log("UPDATE error:", error?.response?.data || error);
      toast.error(
        error?.response?.data?.errors?.[0] ||
        error?.response?.data?.message ||
        error?.message ||
        "Failed to update student"
      );
    } finally {
      setLoading(false);
    }
  };

  // StatusChange
  const handleStatusChange = async (id, status) => {
    // setLoading(true);
    console.log(id, status)
    // setLoading(false);
  };

  // delete mutation

  const handleDelete = (id) => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        console.log(id)
      }
    });
  };


  return (
    <div className="relative w-full">
      {/* main part */}
      <div className={`p-5 ${popOpen ? "pb-16" : ""}`}>
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
                  onClick={() => setOpenAdd(true)}
                  className="flex items-center gap-1 font-bold text-primary-content bg-primary hover:bg-primary-hover px-3 py-2 text-base rounded cursor-pointer whitespace-nowrap"
                >
                  <GoPlusCircle />
                  {t.add}
                </button>
              </div>
            </div>
          </div>

          {/* CARDS */}
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5 mt-2">
            {currentRows.length === 0 ? (
              <div className="col-span-full border border-accent py-4 px-4 whitespace-nowrap text-center">
                {searchTerm ? `No data found for "${searchTerm}".` : "No data available."}
              </div>
            ) : (
              currentRows.map((row) => {
                const cardPhoto =
                  buildPhotoUrl(row?.student_data?.student_photo) ||
                  defaultPhoto;

                return (
                  <div
                    key={row?.id}
                    className="bg-base-200 shadow hover:scale-102 transition-transform duration-300 flex flex-col justify-between rounded-lg overflow-hidden"
                  >
                    <div className="relative">
                      <img
                        src={cardPhoto}
                        alt={row?.student_data?.student_name}
                        className="h-80 w-full object-cover brightness-95 transition-all duration-500"
                      />
                      <div className="absolute inset-0 bg-linear-to-b from-transparent to-black/20 opacity-50"></div>

                      <div className="absolute bottom-3 left-4 text-primary-content font-semibold text-xl drop-shadow-lg">
                        {row?.student_data?.student_name}
                      </div>

                      {/* 3 dots */}
                      <div data-student-menu className="absolute top-1 right-1">
                        <button
                          onClick={() => setPopOpen(popOpen === row?.id ? null : row?.id)}
                          className={`border-none p-2 rounded-3xl cursor-pointer hover:bg-primary-light hover:text-primary ${popOpen === row.id ? "bg-primary-light text-primary" : "text-primary"
                            }`}
                        >
                          <BsThreeDotsVertical className="text-xl cursor-pointer font-bold" />
                        </button>

                        <div
                          className={`absolute top-10 right-0 transition-all duration-200 origin-top ${popOpen === row?.id ? "opacity-100 scale-y-100" : "opacity-0 scale-y-0"
                            } min-w-36 bg-base-200 shadow-lg rounded border border-accent p-2 z-50`}
                        >
                          <button
                            onClick={() => handleStatusChange(row?.id, row?.status)}
                            className="p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                            role="menuitem"
                            disabled={loading}
                          >
                            {row?.status === 0 ? (
                              <span className="flex items-center gap-2">
                                <FaCheck size={18} /> {t.active}
                              </span>
                            ) : (
                              <span className="flex items-center gap-2">
                                <MdBlock size={18} /> {t.inactive}
                              </span>
                            )}
                          </button>

                          <button
                            onClick={() => handleActionUpdate(row?.id)}
                            className="flex items-center gap-2 p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                            role="menuitem"
                          >
                            <GoPencil size={18} /> {t.update}
                          </button>

                          <button
                            onClick={() => handleDelete(row?.id)}
                            className="flex items-center gap-2 p-2 text-sm rounded cursor-pointer hover:bg-neutral w-full text-left"
                            role="menuitem"
                            disabled={loading}
                          >
                            <RiDeleteBinLine size={18} /> {t.delete}
                          </button>
                        </div>
                      </div>
                    </div>

                    <div className="p-4">
                      <ul className="text-primary-content text-left space-y-1">
                        <li>
                          <span className="font-bold">{t.class}:</span>{" "}
                          <span className="font-normal">{row?.class_data?.class_name}</span>
                        </li>
                        <li>
                          <span className="font-bold">{t.section}:</span>{" "}
                          <span className="font-normal">{row?.section_data?.section_name}</span>
                        </li>
                        <li>
                          <span className="font-bold">{t.group}:</span>{" "}
                          <span className="font-normal">{row?.group_data?.group_name}</span>
                        </li>
                        <li>
                          <span className="font-bold">{t.phone}:</span>{" "}
                          <span className="font-normal">{row?.student_data?.student_phone}</span>
                        </li>
                        <li>
                          <span className="font-bold">{t.email}:</span>{" "}
                          <span className="font-normal">{row?.student_data?.student_email}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                );
              })
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

      {/* FILTER PANEL (unchanged) */}
      <div className={openFilter ? "fixed top-0 right-0 w-full h-screen bg-black/20 overflow-y-auto z-40" : "hidden"}>
        <div
          className={`fixed top-0 right-0 w-full lg:w-4/12 bg-base-200 h-screen overflow-y-auto z-40 ${panelHide ? "slide-in" : "slide-out"
            }`}
        >
          <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
            <h1 className="text-base sm:text-lg font-semibold">{t.filter}</h1>
            <div className="flex items-center gap-3">
              <button type="button" onClick={handleFilterReset} className="text-primary-content hover:text-error cursor-pointer">
                <RiResetLeftFill size={24} />
              </button>
              <button type="button" onClick={closeAddPanel} className="text-primary-content hover:text-error cursor-pointer">
                <IoMdClose size={24} />
              </button>
            </div>
          </div>

          <form onSubmit={handleSearchSubmit} className="flex items-center w-full my-3 px-3">
            <input
              type="text"
              id="search"
              name="search"
              placeholder={t.search}
              disabled={!search}
              className={`border-2 border-primary w-full h-10 px-2 rounded-s text-primary-content ${!search ? "bg-gray-200 cursor-not-allowed opacity-70" : ""
                }`}
            />
            <button type="submit" className="bg-primary text-primary-content px-3 py-2 rounded-e font-bold cursor-pointer">
              {search ? t.search : t.reset}
            </button>
          </form>

          <h1 className="px-5 py-2 text-primary font-bold bg-primary-light">{t.table}</h1>
          <div className="px-5 py-2 grid grid-cols-8 gap-2">
            {PAGE_SIZE_OPTIONS_FILTER.map((opt) => (
              <div
                key={opt}
                onClick={() => handleSelectPageSize(opt)}
                className={`${pageSizeValue === opt ? "bg-primary text-primary-content" : "bg-primary-light text-primary"
                  } px-4 py-2 rounded-md hover:bg-primary-hover hover:text-primary-content cursor-pointer`}
              >
                {opt}
              </div>
            ))}
          </div>
          <form className="mt-1 space-y-1" onSubmit={handleFilterSubmit}>


            <h1 className="px-5 py-2 text-primary font-bold bg-primary-light">{t.active}</h1>
            <div className="px-5 py-2 grid grid-cols-3 gap-2">
              <button
                type="button"
                onClick={() => handleStatus("All")}
                className={`px-4 py-2 rounded-md cursor-pointer ${statusValue === "All" ? "bg-primary-hover text-white" : "bg-blue-200 text-primary-content hover:bg-blue-300"
                  }`}
              >
                {t.all}
              </button>

              <button
                type="button"
                onClick={() => handleStatus("Active")}
                className={`px-4 py-2 rounded-md  cursor-pointer ${statusValue === "Active"
                  ? "bg-primary text-primary-content"
                  : "bg-primary-light text-primary hover:bg-primary-hover hover:text-primary-content"
                  }`}
              >
                {t.active}
              </button>

              <button
                type="button"
                onClick={() => handleStatus("Inactive")}
                className={`px-4 py-2 rounded-md cursor-pointer ${statusValue === "Inactive"
                  ? "bg-red-500 text-primary-content"
                  : "bg-red-200 text-red-600 hover:bg-red-500 hover:text-primary-content"
                  }`}
              >
                {t.inactive}
              </button>
            </div>

            <div className="flex items-center justify-end gap-3 px-2 pb-4">
              <button type="button" onClick={closeAddPanel} className="cursor-pointer bg-secondary text-primary-content py-2 px-4 rounded-md">
                {t.cancel}
              </button>
              <button type="submit" className="cursor-pointer bg-primary text-primary-content py-2 px-4 rounded-md">
                {t.apply}
              </button>
            </div>
          </form>
        </div>
      </div>

      {/* CREATE PANEL */}
      <div className={openAdd ? "fixed top-0 right-0 w-full h-screen bg-black/20 overflow-y-auto z-40" : "hidden"}>
        <div
          className={`fixed top-0 right-0 w-full lg:w-4/12 bg-base-200 h-screen overflow-y-auto z-40 ${panelHide ? "slide-in" : "slide-out"
            }`}
        >
          <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
            <h1 className="text-base sm:text-lg font-semibold">{t.create}</h1>

            <button
              type="button"
              onClick={() => {
                reset();
                closeAddPanel();
              }}
              disabled={loading}
              className="text-sm text-primary-content cursor-pointer hover:bg-error hover:text-primary-content"
            >
              <IoMdClose size={24} />
            </button>
          </div>

          <form className="mt-4 space-y-3 px-5" onSubmit={handleCreateSubmit}>
            {/* Class */}
            <div>
              <h1 className="font-semibold text-lg text-primary-content">
                {t.class} <span className="text-error">*</span>
              </h1>
              <input
                type="text"
                // value={studentNameValue || ""}
                // onChange={(e) => setStudentNameValue(e.target.value)}
                className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                required
              />
            </div>

            <div className="flex items-center justify-end gap-5 mt-4 pb-6">
              <button
                type="button"
                onClick={() => {
                  reset();
                  closeAddPanel();
                }}
                disabled={loading}
                className="bg-secondary text-primary-content py-2 px-4 rounded-md cursor-pointer"
              >
                {t.cancel}
              </button>

              <button type="submit" className="bg-primary text-primary-content py-2 px-4 rounded-md cursor-pointer" disabled={loading}>
                {t.create}
              </button>
            </div>
          </form>
        </div>
      </div>

      {/* UPDATE PANEL */}
      {openUpdate && editingRow && (
        <div className="fixed top-0 right-0 w-full h-screen bg-black/20 overflow-y-auto z-40">
          <div
            className={`fixed top-0 right-0 w-full lg:w-4/12 bg-base-200 h-screen overflow-y-auto z-40 ${panelHide ? "slide-in" : "slide-out"
              }`}
          >
            <div className="flex items-center justify-between border-b-2 border-accent px-5 py-3">
              <h1 className="text-base sm:text-lg font-semibold">{t.update}</h1>

              <button
                type="button"
                onClick={() => {
                  closeAddPanel();
                  reset();
                }}
                disabled={loading}
                className="text-sm text-primary-content cursor-pointer hover:text-error"
              >
                <IoMdClose size={24} />
              </button>
            </div>

            <form className="mt-4 space-y-3 px-5 pb-6" onSubmit={handleUpdateSubmit}>
              <div>
                <h1 className="font-semibold text-lg text-primary-content">
                  {t.class} <span className="text-error">*</span>
                </h1>
                <input
                  type="text"
                  // value={studentNameValue || ""}
                  // onChange={(e) => setStudentNameValue(e.target.value)}
                  className="w-full max-h-10 border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                  required
                />
              </div>

              <div className="flex items-center justify-end gap-5 pt-2">
                <button
                  type="button"
                  onClick={() => {
                    closeAddPanel();
                    reset();
                  }}
                  disabled={loading}
                  className="bg-secondary text-primary-content py-2 px-4 rounded-md cursor-pointer"
                >
                  {t.cancel}
                </button>

                <button type="submit" disabled={loading} className="bg-primary text-primary-content py-2 px-4 rounded-md cursor-pointer">
                  {t.update}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Card;