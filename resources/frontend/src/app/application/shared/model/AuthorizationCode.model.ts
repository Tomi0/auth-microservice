export interface AuthorizationCode {
  id: string;
  clientId: string;
  userId: string;
  code: string;
  expiresAt: string;
  createdAt: string;
  updatedAt: string;
}
