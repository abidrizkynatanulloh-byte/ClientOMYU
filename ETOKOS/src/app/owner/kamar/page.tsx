"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  formatTanggal,
  getRoomStatusMeta,
  buildWhatsAppLink,
} from "@/lib/formatters";
import { Room, RoomStatus, Tenant } from "@/types";
import {
  BedDouble,
  Plus,
  Trash2,
  Edit3,
  User,
  Phone,
  ShieldAlert,
  CheckCircle2,
  X,
  MessageCircle,
  Calendar,
  Wallet,
  Building2,
  AlertTriangle,
} from "lucide-react";

export default function OwnerKamarPage() {
  const {
    selectedPropertyId,
    properties,
    rooms,
    tenants,
    addRoom,
    updateRoom,
    deleteRoom,
  } = useKosStore();

  const [floorFilter, setFloorFilter] = useState<number | "ALL">("ALL");
  const [statusFilter, setStatusFilter] = useState<RoomStatus | "ALL">("ALL");
  const [selectedRoom, setSelectedRoom] = useState<Room | null>(null);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [alertMessage, setAlertMessage] = useState<{
    type: "success" | "error";
    text: string;
  } | null>(null);

  // Add Room Form State
  const [newPropId, setNewPropId] = useState(properties[0].id);
  const [newRoomNum, setNewRoomNum] = useState("");
  const [newFloor, setNewFloor] = useState(1);
  const [newType, setNewType] = useState<"Standard" | "Deluxe" | "VIP Suite">("Standard");
  const [newSize, setNewSize] = useState("3x4 m");
  const [newPrice, setNewPrice] = useState(1950000);

  const filteredRooms = rooms.filter((r) => {
    const matchProp = selectedPropertyId === "all" || r.propertyId === selectedPropertyId;
    const matchFloor = floorFilter === "ALL" || r.floor === floorFilter;
    const matchStatus = statusFilter === "ALL" || r.status === statusFilter;
    return matchProp && matchFloor && matchStatus;
  });

  const handleAddRoom = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newRoomNum.trim()) return;
    const res = addRoom({
      propertyId: newPropId,
      roomNumber: newRoomNum,
      floor: newFloor,
      type: newType,
      size: newSize,
      priceMonthly: Math.max(0, Number(newPrice)),
      status: "TERSEDIA",
      facilities: ["AC", "Kamar Mandi Dalam", "Kasur Springbed", "Meja & Lemari", "WiFi"],
      images: [
        "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&auto=format&fit=crop&q=80",
      ],
    });
    setAlertMessage({ type: res.success ? "success" : "error", text: res.message });
    setIsAddModalOpen(false);
    setNewRoomNum("");
  };

  const handleDeleteRoom = (room: Room) => {
    const res = deleteRoom(room.id);
    setAlertMessage({ type: res.success ? "success" : "error", text: res.message });
    if (res.success && selectedRoom?.id === room.id) {
      setSelectedRoom(null);
    }
  };

  const handleChangeStatus = (room: Room, newStatus: RoomStatus) => {
    if (room.status === "TERISI" && newStatus !== "TERISI") {
      setAlertMessage({
        type: "error",
        text: `Kamar #${room.roomNumber} sedang dihuni penghuni aktif. Gunakan alur Check-out untuk mengosongkan kamar.`,
      });
      return;
    }
    updateRoom(room.id, { status: newStatus });
    setSelectedRoom({ ...room, status: newStatus });
    setAlertMessage({
      type: "success",
      text: `Status Kamar #${room.roomNumber} diperbarui menjadi ${newStatus}.`,
    });
  };

  const getTenantForRoom = (room: Room): Tenant | undefined => {
    return tenants.find((t) => t.roomId === room.id || t.id === room.currentTenantId);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
            Denah Kamar & Profil Penghuni
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            Klik kartu kamar pada denah untuk melihat detail penghuni, masa kontrak, deposit, atau mengelola kamar.
          </p>
        </div>

        <button
          onClick={() => setIsAddModalOpen(true)}
          className="px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold transition flex items-center space-x-2 shadow-sm self-start"
        >
          <Plus className="w-4 h-4" />
          <span>Tambah Kamar Baru</span>
        </button>
      </div>

      {/* Business Rule / Feedback Banner */}
      {alertMessage && (
        <div
          className={`p-4 rounded-xl border flex items-start justify-between ${
            alertMessage.type === "error"
              ? "bg-rose-50 border-rose-200 text-rose-800"
              : "bg-emerald-50 border-emerald-200 text-emerald-800"
          }`}
        >
          <div className="flex items-start space-x-2.5 text-xs">
            {alertMessage.type === "error" ? (
              <ShieldAlert className="w-5 h-5 text-rose-600 flex-shrink-0" />
            ) : (
              <CheckCircle2 className="w-5 h-5 text-emerald-600 flex-shrink-0" />
            )}
            <div>
              <p className="font-bold">
                {alertMessage.type === "error" ? "Proteksi Aturan Bisnis" : "Berhasil"}
              </p>
              <p className="mt-0.5">{alertMessage.text}</p>
            </div>
          </div>
          <button
            onClick={() => setAlertMessage(null)}
            className="text-slate-400 hover:text-slate-600"
          >
            <X className="w-4 h-4" />
          </button>
        </div>
      )}

      {/* Legend & Filters */}
      <div className="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-center justify-between gap-4 text-xs">
        {/* Floor Filter */}
        <div className="flex items-center space-x-1.5">
          <span className="font-semibold text-slate-600 mr-1">Lantai:</span>
          {["ALL", 1, 2].map((fl) => (
            <button
              key={fl}
              onClick={() => setFloorFilter(fl as any)}
              className={`px-3 py-1.5 rounded-lg font-bold transition ${
                floorFilter === fl
                  ? "bg-slate-900 text-white"
                  : "bg-slate-100 text-slate-600 hover:bg-slate-200"
              }`}
            >
              {fl === "ALL" ? "Semua Lantai" : `Lantai ${fl}`}
            </button>
          ))}
        </div>

        {/* Status Legend / Filter */}
        <div className="flex flex-wrap items-center gap-2">
          {[
            { id: "ALL", label: "Semua Status", dot: "bg-slate-400" },
            { id: "TERISI", label: "Terisi", dot: "bg-blue-500" },
            { id: "TERSEDIA", label: "Kosong", dot: "bg-emerald-500" },
            { id: "DIPESAN", label: "Dipesan", dot: "bg-amber-500" },
            { id: "PERBAIKAN", label: "Perbaikan", dot: "bg-rose-500" },
          ].map((st) => (
            <button
              key={st.id}
              onClick={() => setStatusFilter(st.id as any)}
              className={`px-3 py-1.5 rounded-lg font-semibold flex items-center space-x-1.5 border transition ${
                statusFilter === st.id
                  ? "border-orange-500 bg-orange-50 text-orange-800"
                  : "border-slate-200 bg-white text-slate-600 hover:bg-slate-50"
              }`}
            >
              <span className={`w-2.5 h-2.5 rounded-full ${st.dot}`} />
              <span>{st.label}</span>
            </button>
          ))}
        </div>
      </div>

      {/* Main Content: Visual Room Grid + Selected Room Inspector */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Denah Kamar Grid */}
        <div className="lg:col-span-2 space-y-6">
          {properties
            .filter((p) => selectedPropertyId === "all" || p.id === selectedPropertyId)
            .map((prop) => {
              const propRooms = filteredRooms.filter((r) => r.propertyId === prop.id);
              if (propRooms.length === 0) return null;

              return (
                <div
                  key={prop.id}
                  className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-2xs"
                >
                  <div className="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div className="flex items-center space-x-2">
                      <Building2 className="w-5 h-5 text-orange-500" />
                      <div>
                        <h2 className="text-base font-bold text-slate-900">{prop.name}</h2>
                        <p className="text-[11px] text-slate-500">
                          {prop.address}, {prop.city}
                        </p>
                      </div>
                    </div>
                    <span className="text-xs font-semibold text-slate-500">
                      {propRooms.length} Kamar ditampilkan
                    </span>
                  </div>

                  {/* Interactive Floor Plan Grid */}
                  <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                    {propRooms.map((room) => {
                      const meta = getRoomStatusMeta(room.status);
                      const tenant = getTenantForRoom(room);
                      const isSelected = selectedRoom?.id === room.id;

                      return (
                        <div
                          key={room.id}
                          onClick={() => setSelectedRoom(room)}
                          className={`cursor-pointer rounded-xl border-2 p-3.5 transition-all flex flex-col justify-between ${
                            isSelected
                              ? "ring-2 ring-orange-500 border-orange-500 bg-orange-50/30 shadow-md"
                              : `${meta.cardBorder} bg-white hover:shadow-sm`
                          }`}
                        >
                          <div>
                            <div className="flex items-center justify-between mb-2">
                              <span className="text-sm font-black text-slate-900">
                                #{room.roomNumber}
                              </span>
                              <span
                                className={`w-2.5 h-2.5 rounded-full ${meta.badge}`}
                                title={meta.label}
                              />
                            </div>

                            <span
                              className={`inline-block px-2 py-0.5 rounded text-[10px] font-bold border ${meta.bg} mb-2`}
                            >
                              {meta.label}
                            </span>

                            {tenant ? (
                              <div className="mt-1 pt-2 border-t border-slate-100">
                                <p className="text-xs font-bold text-slate-800 truncate">
                                  {tenant.name}
                                </p>
                                <p className="text-[10px] text-slate-500 truncate">
                                  s/d {formatTanggal(tenant.contractEnd)}
                                </p>
                              </div>
                            ) : (
                              <div className="mt-1 pt-2 border-t border-slate-100">
                                <p className="text-[11px] text-slate-400 italic">
                                  Belum ada penghuni
                                </p>
                              </div>
                            )}
                          </div>

                          <div className="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <span className="text-slate-500">{room.type}</span>
                            <span className="font-bold text-slate-900">
                              {(room.priceMonthly / 1000000).toFixed(2)}jt
                            </span>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              );
            })}
        </div>

        {/* Right Panel: Room & Tenant Detail Inspector */}
        <div className="space-y-4">
          {selectedRoom ? (
            <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5 sticky top-16">
              <div className="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                  <span className="text-[11px] font-bold uppercase text-orange-600">
                    Detail Kamar & Kontrak
                  </span>
                  <h3 className="text-xl font-black text-slate-900">
                    Kamar #{selectedRoom.roomNumber} (Lantai {selectedRoom.floor})
                  </h3>
                </div>
                <button
                  onClick={() => setSelectedRoom(null)}
                  className="p-1 text-slate-400 hover:text-slate-600 rounded-lg"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>

              {/* Room Info */}
              <div className="grid grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 text-xs">
                <div>
                  <span className="text-slate-400 block">Tipe & Ukuran</span>
                  <span className="font-bold text-slate-800">
                    {selectedRoom.type} ({selectedRoom.size})
                  </span>
                </div>
                <div>
                  <span className="text-slate-400 block">Tarif Sewa / Bulan</span>
                  <span className="font-black text-orange-600">
                    {formatRupiah(selectedRoom.priceMonthly)}
                  </span>
                </div>
              </div>

              {/* Quick Status Changer */}
              <div className="space-y-1.5">
                <label className="text-xs font-bold text-slate-700">Ubah Status Kamar:</label>
                <div className="grid grid-cols-2 gap-1.5 text-xs">
                  {(["TERSEDIA", "TERISI", "DIPESAN", "PERBAIKAN"] as RoomStatus[]).map((st) => (
                    <button
                      key={st}
                      onClick={() => handleChangeStatus(selectedRoom, st)}
                      className={`py-1.5 px-2.5 rounded-lg font-semibold border transition ${
                        selectedRoom.status === st
                          ? "bg-orange-600 text-white border-orange-600"
                          : "bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100"
                      }`}
                    >
                      {st}
                    </button>
                  ))}
                </div>
              </div>

              {/* Tenant Info if occupied */}
              {(() => {
                const tenant = getTenantForRoom(selectedRoom);
                if (!tenant) {
                  return (
                    <div className="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200 text-center space-y-2">
                      <p className="text-xs font-bold text-emerald-900">
                        Kamar Ini Siap Disewakan
                      </p>
                      <p className="text-[11px] text-emerald-700">
                        Tidak ada penghuni aktif. Anda dapat mengubah harga atau menghapus kamar ini.
                      </p>
                    </div>
                  );
                }

                const waUrl = buildWhatsAppLink(
                  tenant.phone,
                  `Halo Kak ${tenant.name} (Kamar #${selectedRoom.roomNumber}), semoga sehat selalu.`
                );

                return (
                  <div className="space-y-4 pt-2 border-t border-slate-100">
                    <div className="flex items-center space-x-3">
                      <img
                        src={tenant.avatarUrl}
                        alt={tenant.name}
                        className="w-12 h-12 rounded-full object-cover border-2 border-orange-500"
                      />
                      <div>
                        <h4 className="text-sm font-bold text-slate-900">{tenant.name}</h4>
                        <p className="text-xs text-slate-500">{tenant.occupation}</p>
                        <p className="text-[11px] text-slate-400">
                          KTP: {tenant.identityNumber} • Asal: {tenant.originCity}
                        </p>
                      </div>
                    </div>

                    {/* Contract & Deposit Box */}
                    <div className="p-3.5 rounded-xl bg-blue-50/50 border border-blue-200/80 space-y-2 text-xs">
                      <div className="flex items-center justify-between">
                        <span className="text-slate-500 flex items-center space-x-1">
                          <Calendar className="w-3.5 h-3.5 text-blue-600" />
                          <span>Masa Kontrak:</span>
                        </span>
                        <span className="font-bold text-slate-800">
                          {formatTanggal(tenant.contractStart)} – {formatTanggal(tenant.contractEnd)}
                        </span>
                      </div>
                      <div className="flex items-center justify-between">
                        <span className="text-slate-500 flex items-center space-x-1">
                          <Wallet className="w-3.5 h-3.5 text-emerald-600" />
                          <span>Uang Deposit:</span>
                        </span>
                        <span className="font-bold text-emerald-700">
                          {formatRupiah(tenant.depositAmount)} ({tenant.depositStatus})
                        </span>
                      </div>
                    </div>

                    {/* Emergency Contact */}
                    <div className="text-xs space-y-1 bg-slate-50 p-3 rounded-xl border border-slate-200">
                      <p className="font-bold text-slate-700">Kontak Darurat:</p>
                      <p className="text-slate-600">
                        {tenant.emergencyContact.name} ({tenant.emergencyContact.relation}) —{" "}
                        {tenant.emergencyContact.phone}
                      </p>
                    </div>

                    <a
                      href={waUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center justify-center space-x-2 transition"
                    >
                      <MessageCircle className="w-4 h-4" />
                      <span>Hubungi Penghuni via WhatsApp</span>
                    </a>
                  </div>
                );
              })()}

              {/* Room Delete Action (Enforces Business Rule!) */}
              <div className="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button
                  onClick={() => handleDeleteRoom(selectedRoom)}
                  className="w-full py-2 px-3 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold flex items-center justify-center space-x-1.5 transition"
                >
                  <Trash2 className="w-3.5 h-3.5" />
                  <span>Hapus Kamar #{selectedRoom.roomNumber}</span>
                </button>
              </div>
            </div>
          ) : (
            <div className="bg-white rounded-2xl border border-slate-200 p-8 text-center space-y-3">
              <BedDouble className="w-10 h-10 text-slate-300 mx-auto" />
              <h3 className="text-sm font-bold text-slate-800">Pilih Kamar pada Denah</h3>
              <p className="text-xs text-slate-500">
                Klik salah satu nomor kamar di sebelah kiri untuk melihat detail penghuni, masa sewa, deposit, atau menguji aturan bisnis hapus kamar.
              </p>
            </div>
          )}
        </div>
      </div>

      {/* Add Room Modal */}
      {isAddModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div className="bg-slate-900 px-6 py-4 text-white flex items-center justify-between">
              <h3 className="font-bold text-sm">Tambah Kamar Kos Baru</h3>
              <button onClick={() => setIsAddModalOpen(false)}>
                <X className="w-5 h-5 text-slate-400 hover:text-white" />
              </button>
            </div>

            <form onSubmit={handleAddRoom} className="p-6 space-y-4 text-xs">
              <div className="space-y-1">
                <label className="font-bold text-slate-700">Pilih Properti *</label>
                <select
                  value={newPropId}
                  onChange={(e) => setNewPropId(e.target.value)}
                  className="w-full px-3 py-2 border rounded-lg bg-white"
                >
                  {properties.map((p) => (
                    <option key={p.id} value={p.id}>
                      {p.name}
                    </option>
                  ))}
                </select>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1">
                  <label className="font-bold text-slate-700">Nomor Kamar *</label>
                  <input
                    type="text"
                    required
                    placeholder="Contoh: 108 / B6"
                    value={newRoomNum}
                    onChange={(e) => setNewRoomNum(e.target.value)}
                    className="w-full px-3 py-2 border rounded-lg"
                  />
                </div>
                <div className="space-y-1">
                  <label className="font-bold text-slate-700">Lantai *</label>
                  <select
                    value={newFloor}
                    onChange={(e) => setNewFloor(Number(e.target.value))}
                    className="w-full px-3 py-2 border rounded-lg bg-white"
                  >
                    <option value={1}>Lantai 1</option>
                    <option value={2}>Lantai 2</option>
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1">
                  <label className="font-bold text-slate-700">Tipe Kamar *</label>
                  <select
                    value={newType}
                    onChange={(e) => setNewType(e.target.value as any)}
                    className="w-full px-3 py-2 border rounded-lg bg-white"
                  >
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="VIP Suite">VIP Suite</option>
                  </select>
                </div>
                <div className="space-y-1">
                  <label className="font-bold text-slate-700">Luas Kamar *</label>
                  <input
                    type="text"
                    required
                    value={newSize}
                    onChange={(e) => setNewSize(e.target.value)}
                    className="w-full px-3 py-2 border rounded-lg"
                  />
                </div>
              </div>

              <div className="space-y-1">
                <label className="font-bold text-slate-700">Harga Sewa Bulanan (Rp) *</label>
                <input
                  type="number"
                  min={100000}
                  required
                  value={newPrice}
                  onChange={(e) => setNewPrice(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg"
                />
              </div>

              <div className="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                <button
                  type="button"
                  onClick={() => setIsAddModalOpen(false)}
                  className="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-orange-600 text-white font-bold"
                >
                  Simpan Kamar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
