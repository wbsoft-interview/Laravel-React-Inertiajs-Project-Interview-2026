import {
    FaBook,
    FaChalkboardTeacher,
    FaClipboardList,
    FaUserGraduate,
} from "react-icons/fa";
import UseAuth from "../../../Hooks/UseAuth";
import { Translations } from "../../../utils/Translations";

const Dashboard = () => {
    const { language } = UseAuth();
    const t = Translations[language];

    return (
        <div className="p-6 space-y-8 bg-base-100 min-h-screen">

            {/* ===== Welcome Section ===== */}
            <div className="flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-primary-content p-8 rounded-2xl shadow-lg">
                <div>
                    <h1 className="text-4xl md:text-5xl font-bold leading-tight">
                        {t.welcome} 👋
                    </h1>
                    <p className="text-xl mt-2 font-semibold opacity-90">
                        {t.dashboardTitle}
                    </p>
                    <p className="mt-3 text-sm opacity-80 max-w-md">
                        {t.dashboardSubtitle}
                    </p>
                </div>

                <div className="text-center border border-white/40 rounded-xl px-10 py-6 backdrop-blur-sm">
                    <h2 className="text-5xl font-bold">{language==="en"?"2025":"২০২৬"}</h2>
                    <p className="uppercase tracking-wide text-sm mt-1">
                        {t.academicYear}
                    </p>
                </div>
            </div>

            {/* ===== Cards Section ===== */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {/* Students */}
                <div className="bg-base-200 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-primary-content">{t.totalStudents}</p>
                            <h3 className="text-3xl font-bold text-primary-content mt-1">
                                {language==="en"?"2025":"২০২৬"}
                            </h3>
                        </div>
                        <div className="bg-indigo-100 text-indigo-600 p-4 rounded-full text-2xl">
                            <FaUserGraduate />
                        </div>
                    </div>
                </div>

                {/* Teachers */}
                <div className="bg-base-200 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-primary-content">{t.totalTeachers}</p>
                            <h3 className="text-3xl font-bold text-primary-content mt-1">
                               {language==="en"?"2025":"২০২৬"}
                            </h3>
                        </div>
                        <div className="bg-green-100 text-green-600 p-4 rounded-full text-2xl">
                            <FaChalkboardTeacher />
                        </div>
                    </div>
                </div>

                {/* Subjects */}
                <div className="bg-base-200 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-primary-content">{t.totalSubjects}</p>
                            <h3 className="text-3xl font-bold text-primary-content mt-1">
                                {language==="en"?"2025":"২০২৬"}
                            </h3>
                        </div>
                        <div className="bg-orange-100 text-orange-600 p-4 rounded-full text-2xl">
                            <FaBook />
                        </div>
                    </div>
                </div>

                {/* Results */}
                <div className="bg-base-200 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-primary-content">{t.publishedResults}</p>
                            <h3 className="text-3xl font-bold text-primary-content mt-1">
                                {language==="en"?"2025":"২০২৬"}
                            </h3>
                        </div>
                        <div className="bg-purple-100 text-purple-600 p-4 rounded-full text-2xl">
                            <FaClipboardList />
                        </div>
                    </div>
                </div>

            </div>

            {/* ===== Footer Text ===== */}
            <div className="text-center text-sm text-gray-400 pt-6">
                © {new Date().getFullYear()} {t.appName} • {t.allRightsReserved}
            </div>

        </div>
    );
};

export default Dashboard;
