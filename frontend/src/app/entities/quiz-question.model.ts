export interface QuizQuestion {
  answers: string[];
  content: string;
}

interface Question {
  content: string;
  answers: string[];
  correctAnswer: string;
  explanation: string;
  yourAnswer: string;
  isCorrect: boolean;
  takenTime: number;
}

export interface QuizModel {
  answeredQuestions: number;
  correctAnswers: number;
  startDifficulty: string;
  endDifficulty: string;
  duration: number;
  questions: Question[];
  feedback: string;
}
