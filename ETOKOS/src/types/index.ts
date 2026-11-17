export type Role = "owner" | "penghuni" | "pengunjung";

export type RoomStatus = "TERSEDIA" | "TERISI" | "DIPESAN" | "PERBAIKAN";

export type BillStatus = "BELUM_BAYAR" | "SEBAGIAN" | "LUNAS" | "TELAT";

export type TicketStatus = "BARU" | "DIPROSES" | "SELESAI";

export type TicketPriority = "RENDAH" | "SEDANG" | "TINGGI" | "DARURAT";

export type KosType = "Campur" | "Putri" | "Putra";

export interface Property {
  id: string;
  name: string;
  slug: string;
  type: KosType;
  address: string;
  city: string;
  province: string;
  postalCode: string;
  latitude: number;
  longitude: number;
  description: string;
  floors: number;
  totalRooms: number;
  coverImage: string;
  images: string[];
  facilities: string[];
  rules: string[];
  managerName: string;
  managerPhone: string;
  bankAccount: {
    bankName: string;
    accountNumber: string;
    accountHolder: string;
    qrisUrl?: string;
  };
  pricingRules: {
    lateFeePerWeek: number; // e.g. 50000
    depositDefault: number; // e.g. 1000000
    wifiFee: number; // e.g. 50000
    cleaningFee: number; // e.g. 25000
  };
}

export interface Room {
  id: string;
  propertyId: string;
  roomNumber: string;
  floor: number;
  type: "Standard" | "Deluxe" | "VIP Suite";
  size: string; // e.g. "3x4 m"
  priceMonthly: number;
  status: RoomStatus;
  facilities: string[];
  images: string[];
  currentTenantId?: string;
}

export interface Tenant {
  id: string;
  name: string;
  phone: string;
  email: string;
  identityNumber: string; // KTP/Passport
  originCity: string;
  occupation: string;
  emergencyContact: {
    name: string;
    relation: string;
    phone: string;
  };
  propertyId: string;
  roomId: string;
  roomNumber: string;
  checkInDate: string; // YYYY-MM-DD
  contractStart: string; // YYYY-MM-DD
  contractEnd: string; // YYYY-MM-DD
  depositAmount: number;
  depositStatus: "DITAHAN" | "DIKEMBALIKAN" | "DIPOTONG";
  monthlyRent: number;
  avatarUrl: string;
}

export interface BillItem {
  id: string;
  name: string; // e.g. "Sewa Kamar", "Listrik", "Air", "WiFi", "Denda"
  amount: number;
  description?: string;
}

export interface PaymentProof {
  uploadedAt: string;
  imageUrl: string;
  amount: number;
  method: "TRANSFER_BCA" | "TRANSFER_MANDIRI" | "QRIS" | "TUNAI";
  status: "PENDING" | "APPROVED" | "REJECTED";
  verifiedAt?: string;
  verifiedBy?: string;
  note?: string;
}

export interface Bill {
  id: string;
  invoiceNumber: string; // e.g. INV-2026-06-001
  propertyId: string;
  tenantId: string;
  tenantName: string;
  roomId: string;
  roomNumber: string;
  billingMonth: string; // e.g. "2026-06"
  periodName: string; // e.g. "Juni 2026"
  dueDate: string; // YYYY-MM-DD
  subtotal: number;
  lateFee: number;
  totalAmount: number;
  paidAmount: number;
  remainingAmount: number;
  status: BillStatus;
  items: BillItem[];
  paymentProofs: PaymentProof[];
  paymentCorrectionNote?: string;
  createdAt: string;
  paidAt?: string;
}

export interface TicketComment {
  id: string;
  authorName: string;
  authorRole: Role;
  text: string;
  createdAt: string;
}

export interface Ticket {
  id: string;
  ticketNumber: string; // e.g. TKT-2026-001
  propertyId: string;
  propertyName: string;
  roomId: string;
  roomNumber: string;
  tenantId: string;
  tenantName: string;
  category: "AC" | "Plumbing & Sanitasi" | "Listrik" | "WiFi & Jaringan" | "Kamar & Mebel" | "Kebersihan & Area Umum";
  title: string;
  description: string;
  priority: TicketPriority;
  status: TicketStatus;
  imageUrl?: string;
  assignedTechnician?: {
    name: string;
    phone: string;
    speciality: string;
  };
  repairCost?: number;
  completionNote?: string;
  completedAt?: string;
  comments: TicketComment[];
  createdAt: string;
}

export interface Expense {
  id: string;
  propertyId: string;
  date: string; // YYYY-MM-DD
  category:
    | "Perbaikan & Perawatan"
    | "Listrik Fasilitas Umum"
    | "Air PAM"
    | "Internet & TV"
    | "Gaji Staf & Keamanan"
    | "Kebersihan & Sampah"
    | "Operasional Lainnya";
  title: string;
  amount: number;
  description: string;
  recipient: string;
  receiptUrl?: string;
  relatedTicketId?: string;
  createdAt: string;
}

export interface AuditLog {
  id: string;
  timestamp: string;
  action: string;
  category: "TAGIHAN" | "PEMBAYARAN" | "KELUHAN" | "KAMAR" | "PENGELUARAN" | "KONTRAK";
  description: string;
  userName: string;
  userRole: Role;
}

export interface Announcement {
  id: string;
  propertyId: string; // or 'ALL'
  title: string;
  content: string;
  category: "PENTING" | "INFO" | "PERAWATAN";
  date: string;
  author: string;
}
