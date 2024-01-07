export interface QuizQuestion {
  content: string;
  options: string[];
  correctAnswer: string;
}

export interface Question {
  content: string;
  answers: string[];
  correctAnswer: string;
  explanation: string;
  yourAnswer: string;
  isCorrect: boolean;
  takenTime: number;
}

export interface AssessmentDetails {
  assessmentTypeId: string;
  assessmentTypeName: string;
  answeredQuestions: number;
  correctAnswers: number;
  duration: number;
  questions: Question[];
}

export interface QuizModel {
  assessmentId: string;
  assessmentState: string;
  userDeviceId: string;
  CategoryId: string;
  LanguageId: string;
  difficultyAtStart: string;
  difficultyAtEnd: string;
  startTime: string;
  endTime: string;
  feedback: string;
  assessmentDetails: AssessmentDetails;
}

export interface CodeSnippet {
  code: string;
  correctSolution: string;
}

export interface FreeText {
  message: string;
  sender: string;
}
